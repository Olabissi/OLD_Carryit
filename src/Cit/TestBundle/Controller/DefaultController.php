<?php

namespace Cit\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;
use Cit\TestBundle\Entity\Voyage;
use Cit\TestBundle\Entity\Colis;
use Cit\TestBundle\Entity\VoyageColis;
use Cit\TestBundle\Form\VoyageType;
use Cit\TestBundle\Form\VoyageHandler;
use Cit\TestBundle\Form\ColisType;
use Cit\TestBundle\Form\ColisHandler;
use Cit\UserBundle\Entity\User;
use Cit\UserBundle\Form\UserForm;
use Cit\UserBundle\Form\UserHandler;
use FOS\UserBundle\Model\UserInterface;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $name = $this->getAnonym();

        $request = $this->container->get('request');
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $session = $request->getSession();
        /* @var $session \Symfony\Component\HttpFoundation\Session */

        $error = $this->getError($request, $session);
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
    	
        //INSPIRE DE registerAction DU FOSUserBundle:Registration
        $userform = $this->container->get('fos_user.registration.form')->createView();
        $theme = $this->container->getParameter('fos_user.template.theme');

        return $this->render('CitTestBundle:Default:index.html.twig', 
        	array('lastusername' => $lastUsername, 
                  'error' => $error, 
                  'token' => $csrfToken,
                  'userform' => $userform,
                  'theme' => $theme,
                  'name' => $name,
                  ));
    }

    public function indexUserActivityAction()
    {
        //On récupère l'utilisateur connecté
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        //Traiter le cas où le user n'est pas authentifié
        if( ! is_object($current_user) )
        {
            throw new AccessDeniedException("Vous n\'êtes pas authentifié.
                Veuillez vous authentifier ou vous inscrire");
        }

        //récupération des paramètres pour le topmenu
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        //récupération des éléments dans la BDD
        $em = $this->getDoctrine()->getEntityManager();

        $packet_entities = $this->getDoctrine()
            ->getRepository('CitTestBundle:Colis')
            ->findByUser($current_user->getId());

        $trip_entities = $this->getDoctrine()
            ->getRepository('CitTestBundle:Voyage')
            ->findByUser($current_user->getId());

        return $this->render('CitTestBundle:Default:useractivity.html.twig', array(
            'packets' => $packet_entities,
            'trips' => $trip_entities,
            'name' => $current_user-> getUsername(),
            'error' => $error,
        ));
    }

    // addTrip() permet à un utilisateur connecté de proposer un voyage
    public function addTripAction()
    {
        //On récupère l'utilisateur connecté
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        //Traiter le cas où le user n'est pas authentifié
        if( ! is_object($current_user) )
        {
            throw new AccessDeniedException(" Vous n\'êtes pas authentifié.
                Veuillez vous authentifier ou vous inscrire ");
        }

        //récupération des erreurs pour le topmenu
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

    	//On crée un objet Voyage.
        $voyage = new Voyage;

    	//À partir du formBuilder, on génère le formulaire.
        $form = $this->createForm(new VoyageType, $voyage);

        //On crée le gestionnaire pour ce formulaire, avec les outils dont il a besoin
        $formHandler = new VoyageHandler($form, $this->get('request'), $this->getDoctrine()->getEntityManager());

        //On exécute le traitement du formulaire. S'il retourne 0, alors le formulaire a bien été traité
        $result = $formHandler->process($current_user);
        if( 0 == $result )
        {
            $this->get('session')->setFlash('notice', 'Nouveau Voyage Validé!');
            return $this->redirect( $this->generateUrl('CitTestBundle_useractivitypage'));
        }
        else
        {
            if ('' != $this->TripFlashes($result))
            {
                $this->get('session')->setFlash('warning', $this->TripFlashes($result));
            }
        }

        //Et s'il retourne false alors la requête n'était pas en POST ou le formulaire non valide.
        //On réaffiche donc le formulaire.
        return $this->render('CitTestBundle:Default:newtrip.html.twig', 
            array('form' => $form->createView(),'name' => $current_user ->getUsername(),'error' => $error)
            );
    }

    //addPacket() permet à un utilisateur connecté de proposer un colis
    public function addPacketAction()
    {
        //On récupère l'utilisateur connecté
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        //Traiter le cas où le user n'est pas authentifié
        if( ! is_object($current_user) )
        {
            throw new AccessDeniedException(" Vous n\'êtes pas authentifié.
                Veuillez vous authentifier ou vous inscrire ");
        }

        //récupération des paramètres pour le topmenu
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        // On crée un objet Colis.
        $colis = new Colis;

        // À partir du formBuilder, on génère le formulaire.
        $form = $this->createForm(new ColisType, $colis);

        // On crée le gestionnaire pour ce formulaire, avec les outils dont il a besoin
        $formHandler = new ColisHandler($form, $this->get('request'), $this->getDoctrine()->getEntityManager());

        //On exécute le traitement du formulaire. S'il retourne true, alors le formulaire a bien été traité
        $result = $formHandler->process($current_user);
        if( 0 == $result )
        {
            $this->get('session')->setFlash('notice', 'Nouveau Colis Validé!');
            return $this->redirect( $this->generateUrl('CitTestBundle_useractivitypage'));
        }
        else
        {
            if ('' != $this->PacketFlashes($result))
            {
                $this->get('session')->setFlash('warning', $this->PacketFlashes($result));
            }
        }

        //Et s'il retourne autre chose alors la requête n'était pas en POST ou le formulaire non valide.
        //On réaffiche donc le formulaire.
        return $this->render('CitTestBundle:Default:newpacket.html.twig', 
            array('form' => $form->createView(),'name' => $current_user ->getUsername(), 'error' => $error)
            );
    }

    public function editTripAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:Voyage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('entité voyage introuvable.');
        }

        $editForm = $this->createForm(new VoyageType(), $entity);
        
        //récupération des paramètres pour le topmenu
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        //Traiter le cas où le user n'est pas authentifié
        if( ! is_object($current_user) )
        {
            throw new AccessDeniedException(" Vous n\'êtes pas authentifié.
                Veuillez vous authentifier ou vous inscrire ");
        }
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        return $this->render('CitTestBundle:Voyage:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'name' => $current_user->getUsername(),
            'error' => $error,
        ));
    }

    public function updateTripAction($id)
    {
        //On récupère l'utilisateur connecté
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        //Traiter le cas où le user n'est pas authentifié
        if( ! is_object($current_user) )
        {
            throw new AccessDeniedException(" Vous n\'êtes pas authentifié.
                Veuillez vous authentifier ou vous inscrire ");
        }

        //récupération des erreurs pour le topmenu
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:Voyage')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('entité voyage introuvable.');
        }

        $editForm   = $this->createForm(new VoyageType(), $entity);

        //On crée le gestionnaire pour ce formulaire, avec les outils dont il a besoin
        $formHandler = new VoyageHandler($editForm, $this->get('request'), $this->getDoctrine()->getEntityManager());

        //On exécute le traitement du formulaire. S'il retourne 0, alors le formulaire a bien été traité
        $result = $formHandler->process($current_user);
        if( 0 == $result )
        {
            $this->get('session')->setFlash('notice', 'Voyage modifié!');
            return $this->redirect( $this->generateUrl('CitTestBundle_useractivitypage'));
        }
        else
        {
            if ('' != $this->TripFlashes($result))
            {
                $this->get('session')->setFlash('warning', $this->TripFlashes($result));
            }
        }

        //si editForm n'est pas valide on régènère la page
        return $this->render('CitTestBundle:Voyage:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'name' => $current_user ->getUsername(),
            'error' => $error
            ));
    }

    public function cancelTripAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:Voyage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('entité voyage introuvable.');
        }

        //récupération des paramètres pour l'affichage du topmenu
        $name = $this->getAnonym();
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        return $this->render('CitTestBundle:Voyage:cancel.html.twig', array(
            'entity'      => $entity,
            'name' => $name,
            'error' => $error,
        ));
    }

    public function deleteTripAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('CitTestBundle:Voyage')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('entité voyage introuvable.');
        }
        
        $em->remove($entity);
        $em->flush();

        $this->get('session')->setFlash('notice', 'Voyage supprimé!');
        return $this->redirect( $this->generateUrl('CitTestBundle_useractivitypage'));
    }

    public function findTripAction($id)
    {
        //On récupère l'utilisateur connecté
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        //Traiter le cas où le user n'est pas authentifié
        if( ! is_object($current_user) )
        {
            throw new AccessDeniedException("Vous n\'êtes pas authentifié.
                Veuillez vous authentifier ou vous inscrire");
        }

        //on récupère les erreurs éventuelles
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        //on récupère l'entité correspondant au colis concerné
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('CitTestBundle:Colis')->find($id);

        //on récupère l'id de la ville de départ du colis, ça permettra de rechercher un voyage indépendamment de la langue
        $depart = $entity->getVilleDepart();
        //$depart = $em->getRepository('CitTestBundle:City')->findOneBy(array('frenchname' => $depart));
        //$id_depart = $depart->getId();

        //on récupère l'id de la ville d'arrivée du colis, ça permettra de rechercher un voyage indépendamment de la langue
        $arrivee = $entity->getVilleArrivee();
        //$arrivee = $em->getRepository('CitTestBundle:City')->findOneBy(array('frenchname' => $arrivee));
        //$id_arrivee = $arrivee->getId();

        //on récupère la date de livraison souhaitée pour le colis
        $date = $entity->getDateLivraisonSouhaitee();

        //on récupère les voyages présentant la même ville de départ et la même ville d'arrivée 
        //et qui n'ont pas déjà trouvé leur colis
        $repo = $em->getRepository('CitTestBundle:Voyage');
        $result = $repo->findBy(
            array('ville_depart' => $depart, 'ville_arrivee' => $arrivee),
            array('date_depart'=>'asc'),
            20
            );

        //parmi les voyages trouvés, on enlève ceux effectués par le user courant
        $result = $this->NotMyOwnTripsOrPackets($result,$current_user->getId());

        //parmi les voyages trouvés, on enlève ceux dont la date de départ est déjà passée
        $result = $this->futureTrips($result);

        return $this->render('CitTestBundle:Default:tripsfound.html.twig', array(
            'packet' => $entity,
            'number' => count($result),
            'result'      => $result,
            'name' => $current_user-> getUsername(),
            'error' => $error,
        ));
    }

    public function editPacketAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:Colis')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('entité colis introuvable.');
        }

        $editForm = $this->createForm(new ColisType(), $entity);

        //récupération de l'utilisateur courant
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        //Traiter le cas où le user n'est pas authentifié
        if( ! is_object($current_user) )
        {
            throw new AccessDeniedException(" Vous n\'êtes pas authentifié.
                Veuillez vous authentifier ou vous inscrire ");
        }

        //récupération des erreurs
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        return $this->render('CitTestBundle:Colis:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'name' => $current_user-> getUsername(),
            'error' => $error,
        ));
    }

    public function updatePacketAction($id)
    {
        //On récupère l'utilisateur connecté
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        //Traiter le cas où le user n'est pas authentifié
        if( ! is_object($current_user) )
        {
            throw new AccessDeniedException(" Vous n\'êtes pas authentifié.
                Veuillez vous authentifier ou vous inscrire ");
        }

        //récupération des erreurs pour le topmenu
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('CitTestBundle:Colis')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('entité colis introuvable.');
        }

        //On crée le formulaire d'édition de colis
        $editForm   = $this->createForm(new ColisType(), $entity);

        //On crée le gestionnaire pour ce formulaire, avec les paramètres dont il a besoin
        $formHandler = new ColisHandler($editForm, $this->get('request'), $this->getDoctrine()->getEntityManager());

        //On exécute le traitement du formulaire. S'il retourne 0, alors le formulaire a bien été traité
        $result = $formHandler->process($current_user);
        if( 0 == $result )
        {
            $this->get('session')->setFlash('notice', 'Colis modifié!');
            return $this->redirect( $this->generateUrl('CitTestBundle_useractivitypage'));
        }
        else 
        {
            if ('' != $this->PacketFlashes($result))
            {
                $this->get('session')->setFlash('warning', $this->PacketFlashes($result));
            }
        }

        //si editForm n'est pas valide on régènère la page
        return $this->render('CitTestBundle:Colis:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'name' => $current_user-> getUsername(),
            'error' => $error,
            )); 
    }

    public function cancelPacketAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:Colis')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('entité colis introuvable.');
        }

        //récupération des paramètres pour l'affichage du topmenu
        $name = $this->getAnonym();
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        return $this->render('CitTestBundle:Colis:cancel.html.twig', array(
            'entity'=> $entity,
            'name' => $name,
            'error' => $error,
        ));   
    }

    public function deletePacketAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('CitTestBundle:Colis')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('entité colis introuvable.');
        }
        
        $em->remove($entity);
        $em->flush();

        $this->get('session')->setFlash('notice', 'Colis supprimé !');
        return $this->redirect( $this->generateUrl('CitTestBundle_useractivitypage'));  
    }

    //findPacket(id) permet à l'utilisateur authentifié de rechercher des voyages correspondant au colis d'identifiant id
    public function findPacketAction($id)
    {
        //On récupère l'utilisateur connecté
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        //Traiter le cas où le user n'est pas authentifié
        if( ! is_object($current_user) )
        {
            throw new AccessDeniedException("Vous n\'êtes pas authentifié.
                Veuillez vous authentifier ou vous inscrire");
        }

        //on récupère les erreurs éventuelles
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        //on récupère l'entité correspondant au voyage concerné
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('CitTestBundle:Voyage')->find($id);

        //on récupère l'id de la ville de départ du voyage, ça permettra de rechercher un colis indépendamment de la langue
        $depart = $entity->getVilleDepart();
        //$depart = $em->getRepository('CitTestBundle:City')->findOneBy(array('frenchname' => $depart));
        //$id_depart = $depart->getId();

        //on récupère l'id de la ville d'arrivée du voyage, ça permettra de rechercher un colis indépendamment de la langue
        $arrivee = $entity->getVilleArrivee();
        //$arrivee = $em->getRepository('CitTestBundle:City')->findOneBy(array('frenchname' => $arrivee));
        //$id_arrivee = $arrivee->getId();

        //on récupère la date de départ du voyageur
        $date = $entity->getDateDepart();

        //on récupère les colis présentant la même ville de départ et la même ville d'arrivée 
        //et qui n'ont pas déjà trouvé leur voyageur
        $repo = $em->getRepository('CitTestBundle:Colis');
        $result = $repo->findBy(
            array('ville_depart' => $depart, 'ville_arrivee' => $arrivee),
            array('date_livraison_souhaitee'=>'asc'),
            20
            );

        //parmi les voyages trouvés, on enlève ceux effectués par le user courant
        $result = $this->NotMyOwnTripsOrPackets($result,$current_user->getId());

        return $this->render('CitTestBundle:Default:packetsfound.html.twig', array(
            'trip' => $entity,
            'number' => count($result),
            'result'      => $result,
            'name' => $current_user-> getUsername(),
            'error' => $error,
        ));
    }

    //seeTripsOrPackets() permet à un visiteur ou un utilisateur connecté de rechercher des voyages ou des colis correspondant à une 
    //ville de départ et une ville d'arrivée que le visiteur/utilisateur précise sur la page d'accueil
    public function seeTripsOrPacketsAction()
    {
        //on récupère les les villes de départ et d'arrivée saisies par l'utilisateur
        $depart = $_POST['_depart'];
        $arrivee = $_POST['_arrivee'];

        //On vérifie que ces villes sont bien présentes dans la BDD
        $em = $this->getDoctrine()->getEntityManager();
        $departok = $em->getRepository('CitTestBundle:City')->isCity($depart);
        if (!$departok)
        {
            $this->get('session')->setFlash('nocity', $this->CityFlashes(1));
            return $this->redirect( $this->generateUrl('CitTestBundle_homepage'));
        }
        $arriveeok = $em->getRepository('CitTestBundle:City')->isCity($arrivee);
        if (!$arriveeok)
        {
            $this->get('session')->setFlash('nocity', $this->CityFlashes(2));
            return $this->redirect( $this->generateUrl('CitTestBundle_homepage'));
        }

        //On récupère l'utilisateur connecté
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        
        //on récupère les erreurs éventuelles
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        if($this->getRequest()->get('_submit')=='Chercher un voyageur')
        {
            $result = $em->getRepository('CitTestBundle:Voyage')->findBy(
                array('ville_depart' => $depart, 'ville_arrivee' => $arrivee),
                array('date_depart'=>'asc'),
                20
                );

            //parmi les voyages trouvés, on enlève ceux dont la date de départ est déjà passée
            $result = $this->futureTrips($result);

            if( is_object($current_user) )
            {
                //parmi les voyages trouvés, on enlève ceux effectués par le user courant
                $result = $this->NotMyOwnTripsOrPackets($result,$current_user->getId());

                return $this->render('CitTestBundle:Default:tripsfoundhomepage.html.twig', array(
                    'number' => count($result),
                    'result'      => $result,
                    'name' => $current_user-> getUsername(),
                    'error' => $error,
                    'depart' => $depart,
                    'arrivee' => $arrivee,
                ));   
            }
            else
            {
                return $this->render('CitTestBundle:Default:tripsfoundhomepage.html.twig', array(
                    'number' => count($result),
                    'result'      => $result,
                    'name' => 'anonymous',
                    'error' => $error,
                    'depart' => $depart,
                    'arrivee' => $arrivee,
                ));   
            }
        }
        else
        {
            $result = $em->getRepository('CitTestBundle:Colis')->findBy(
                array('ville_depart' => $depart, 'ville_arrivee' => $arrivee),
                array('date_livraison_souhaitee'=>'asc'),
                20
                );

            if( is_object($current_user) )
            {
                //parmi les voyages trouvés, on enlève ceux effectués par le user courant
                $result = $this->NotMyOwnTripsOrPackets($result,$current_user->getId());

                return $this->render('CitTestBundle:Default:packetsfoundhomepage.html.twig', array(
                    'number' => count($result),
                    'result'      => $result,
                    'name' => $current_user-> getUsername(),
                    'error' => $error,
                    'depart' => $depart,
                    'arrivee' => $arrivee,
                ));   
            }
            else
            {
                return $this->render('CitTestBundle:Default:packetsfoundhomepage.html.twig', array(
                    'number' => count($result),
                    'result'      => $result,
                    'name' => 'anonymous',
                    'error' => $error,
                    'depart' => $depart,
                    'arrivee' => $arrivee,
                ));   
            }
        } 
    }

    public function showWhoAction()
    {
        //récupération des paramètres pour l'affichage du topmenu
        $name = $this->getAnonym();
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        return $this->render('CitTestBundle:Default:whoarewe.html.twig', 
            array('lastusername' => $lastUsername, 
                  'error' => $error, 
                  'token' => $csrfToken,
                  'name' => $name,
                  ));
    }

    public function showHowAction()
    {
        //récupération des paramètres pour l'affichage du topmenu
        $name = $this->getAnonym();
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        return $this->render('CitTestBundle:Default:howitworks.html.twig', 
            array('lastusername' => $lastUsername, 
                  'error' => $error, 
                  'token' => $csrfToken,
                  'name' => $name,
                  ));
    }

    public function showTermsAction()
    {
        //récupération des paramètres pour l'affichage du topmenu
        $name = $this->getAnonym();
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        return $this->render('CitTestBundle:Default:terms.html.twig', 
            array('lastusername' => $lastUsername, 
                  'error' => $error, 
                  'token' => $csrfToken,
                  'name' => $name,
                  ));
    }

    public function getCitiesAction()
    {
        $term=isset($_GET['term'])?$_GET['term']:'Aa';
        $em = $this->getDoctrine()->getEntityManager();
        $result = $em->getRepository('CitTestBundle:City')->myCities($term);

        return $this->render('CitTestBundle:Default:cities.json.twig', array('tableau' => $result));
    }

    protected function getAnonym()
    {
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        $name = "anonym";
        if (is_object($current_user))
        {
            $name = $current_user->getUsername();
        }

        return $name;
    }

    protected function getError($request, $session)
    {
        //INSPIRE DE loginAction DU FOSUserBundle:Security

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }

        return $error;
    }

    protected function NotMyOwnTripsOrPackets($array,$currentid)
    {
        $tableau = array();  
        
        foreach ($array as $value) 
        {
            $id_temp = $value->getUser()->getId();
            if ($currentid != $id_temp)
            {
                //le voyage n'est pas un des voyages de l'utilisateur courant
                $tableau[]=$value;
            }
        }
        
        return $tableau;
    }

    protected function futureTrips($array)
    {
        $tableau = array(); 
        $now = date('Ymd'); 

        foreach ($array as $value) 
        {
            $date = $value->getDateDepart();
            $date = $date->format('Ymd');

            if ($date >= $now)
            {
                //le voyage n'est pas encore passé
                $tableau[]=$value;
            }
        }

        return $tableau;
    }

    protected function TripFlashes($number)
    {
        $msg ='';

        if ( 1 == $number )
        {
            //$msg = 'choisissez la ville de départ parmi les suggestions.';
            $msg = $this->CityFlashes($number);
        }
        elseif ( 2 == $number )
        {
            //$msg = 'choisissez la ville d\'arrivée parmi les suggestions.';
            $msg = $this->CityFlashes($number);
        }
        elseif ( 3 == $number )
        {
            $msg = 'Pour la date de départ, choisissez la date d\'aujourd\'hui ou une date dans le futur.';
        }
        elseif ( 4 == $number )
        {
            $msg = 'la date d\'arrivée ne doit pas être antérieure à la date de départ.';
        }
        elseif ( 5 == $number )
        {
            $msg = 'le nombre de kilogrammes disponibles doit être supérieur à 0.';
        }
        elseif ( 6 == $number )
        {
            $msg = 'Trop de kilogrammes disponibles. Le nombre maximum de kilos disponibles est 23.';
        }
        elseif ( 7 == $number )
        {
            $msg = 'le prix proposé (en € par kilo) ne doit pas être inférieur à 0.';
        }
        elseif ( 8 == $number )
        {
            $msg = 'Pour cette ville de départ, vous avez déjà proposé un voyage à cette date de départ';
        }

        return $msg;
    }

    protected function PacketFlashes($number)
    {
        $msg = '';

        if ( 1 == $number )
        {
            //$msg = 'choisissez la ville de départ parmi les suggestions.';
            $msg = $this->CityFlashes($number);
        }
        elseif ( 2 == $number )
        {
            //$msg = 'choisissez la ville d\'arrivée parmi les suggestions.';
            $msg = $this->CityFlashes($number);
        }
        elseif ( 3 == $number )
        {
            $msg = 'le poids en kilos du colis doit être supérieur à 0.';
        }
        elseif ( 4 == $number )
        {
            $msg = 'Colis trop lourd. Le poids maximal d\'un colis est de 23 kilogrammes.';
        }
        elseif ( 5 == $number )
        {
            $msg = 'Sélectionnez une catégorie de colis parmi les suggestions.';
        }
        elseif ( 6 == $number )
        {
            $msg = 'Pour la date de livraison souhaitée, choisissez une date dans le futur.';
        }
        elseif ( 7 == $number )
        {
            $msg = 'Pour ce trajet, vous avez déjà proposé un colis à cette date de livraison';
        }

        return $msg;
    }

    protected function CityFlashes($number)
    {
        $msg = '';

        if ( 1 == $number )
        {
            $msg = 'Si vous l\'avez bien orthographiée, alors la ville de départ n\'est pas référencée. 
            Contactez-nous si vous souhaitez que cette ville fasse partie de nos villes référencées.';

        }
        elseif ( 2 == $number )
        {
            $msg = 'Si vous l\'avez bien orthographiée, alors la ville d\'arrivée n\'est pas référencée. 
            Contactez-nous si vous souhaitez que cette ville fasse partie de nos villes référencées.';
        }

        return $msg;
    }
}
