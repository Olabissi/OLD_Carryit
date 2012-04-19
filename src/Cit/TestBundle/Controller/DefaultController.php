<?php

namespace Cit\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;

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

    public function addUserAction()
    {
        //On récupère l'utilisateur connecté
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        //Traiter le cas où le user est déjà authentifié : le renvoyer sur la page d'accueil
        if( is_object($current_user) )
        {
            $name = "Vous êtes déjà inscrit";

            return $this->render('CitTestBundle:Default:index.html.twig', 
            array('name' => $name));
        }

        // On crée un objet User.
        $user = new User;

        // À partir du formBuilder, on génère le formulaire.
        $form = $this->createForm(new UserForm, $user);

        // On crée le gestionnaire pour ce formulaire, avec les outils dont il a besoin
        $formHandler = new UserHandler($form, $this->get('request'), $this->getDoctrine()->getEntityManager());

        // On exécute le traitement du formulaire. S'il retourne true, alors le formulaire a bien été traité
        if( $formHandler->process() )
        {

            //$this->get('session')->setFlash('notice', 'Inscription Validée!');
            return $this->redirect( $this->generateUrl('CitTestBundle_adduserpage'));
        }

        // Et s'il retourne false alors la requête n'était pas en POST ou le formulaire non valide.
        // On réaffiche donc le formulaire...
        return $this->render('CitTestBundle:Default:newuser.html.twig', 
            array('form' => $form->createView(),)
            );
    }

    public function addTripAction()
    {
    	//On crée un objet Voyage.
        $voyage = new Voyage;

    	//À partir du formBuilder, on génère le formulaire.
        $form = $this->createForm(new VoyageType, $voyage);

        //On crée le gestionnaire pour ce formulaire, avec les outils dont il a besoin
        $formHandler = new VoyageHandler($form, $this->get('request'), $this->getDoctrine()->getEntityManager());

        //On récupère l'utilisateur connecté
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        //Traiter le cas où le user n'est pas authentifié
        if( ! is_object($current_user) )
        {
            throw new AccessDeniedException(" Vous n\'êtes pas authentifié.
                Veuillez vous authentifier ou vous inscrire ");

            //return $this->render('CitTestBundle:Default:newtrip.html.twig', 
            //array('form' => $form->createView(),));

            //$msg = 'Vous n\'êtes pas authentifié. Veuillez vous authentifier';
            //return $this->render('CitUserBundle:Default:login.html.twig', 
            //array('message' => $msg));
        }

        //On exécute le traitement du formulaire. S'il retourne true, alors le formulaire a bien été traité
        if( $formHandler->process($current_user) )
        {
            $this->get('session')->setFlash('notice', 'Nouveau Voyage Validé!');
            return $this->redirect( $this->generateUrl('CitTestBundle_useractivitypage'));
        }

        //récupération des paramètres pour le topmenu
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);
        $name = $current_user ->getUsername();

    	//Et s'il retourne false alors la requête n'était pas en POST ou le formulaire non valide.
        //On réaffiche donc le formulaire.
    	return $this->render('CitTestBundle:Default:newtrip.html.twig', 
    		array('form' => $form->createView(),'name' => $name,'error' => $error)
    		);
    }

    public function addPacketAction()
    {
        // On crée un objet Colis.
        $colis = new Colis;

        // À partir du formBuilder, on génère le formulaire.
        $form = $this->createForm(new ColisType, $colis);

        // On crée le gestionnaire pour ce formulaire, avec les outils dont il a besoin
        $formHandler = new ColisHandler($form, $this->get('request'), $this->getDoctrine()->getEntityManager());

        //On récupère l'utilisateur connecté
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        //Traiter le cas où le user n'est pas authentifié
        if( ! is_object($current_user) )
        {
            throw new AccessDeniedException(" Vous n\'êtes pas authentifié.
                Veuillez vous authentifier ou vous inscrire ");

            //return $this->render('CitTestBundle:Default:newpacket.html.twig', 
            //array('form' => $form->createView(),));

            //$msg = 'Vous n\'êtes pas authentifié. Veuillez vous authentifier';
            //return $this->render('CitUserBundle:Default:login.html.twig', 
            //array('message' => $msg));
        }

        //On exécute le traitement du formulaire. S'il retourne true, alors le formulaire a bien été traité
        if( $formHandler->process($current_user) )
        {
            $this->get('session')->setFlash('notice', 'Nouveau Colis Validé!');
            return $this->redirect( $this->generateUrl('CitTestBundle_useractivitypage'));
        }

        //récupération des paramètres pour le topmenu
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);
        $name = $current_user ->getUsername();

        //Et s'il retourne false alors la requête n'était pas en POST ou le formulaire non valide.
        //On réaffiche donc le formulaire.
        return $this->render('CitTestBundle:Default:newpacket.html.twig', 
            array('form' => $form->createView(),'name' => $name, 'error' => $error)
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
        $name = $this->getAnonym();
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        return $this->render('CitTestBundle:Voyage:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'name' => $name,
            'error' => $error,
        ));
    }

    public function updateTripAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:Voyage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('entité voyage introuvable.');
        }

        $editForm   = $this->createForm(new VoyageType(), $entity);

        $request = $this->getRequest();
        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('notice', 'Voyage modifié!');
            return $this->redirect( $this->generateUrl('CitTestBundle_useractivitypage'));
        }

        //si editForm n'est pas valide on régènère la page
        return $this->render('CitTestBundle:Voyage:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
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

    public function editPacketAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:Colis')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('entité colis introuvable.');
        }

        $editForm = $this->createForm(new ColisType(), $entity);

        //récupération des paramètres pour le topmenu
        $name = $this->getAnonym();
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        return $this->render('CitTestBundle:Colis:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'name' => $name,
            'error' => $error,
        ));
    }

    public function updatePacketAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:Colis')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('entité colis introuvable.');
        }

        $editForm   = $this->createForm(new ColisType(), $entity);

        $request = $this->getRequest();
        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('notice', 'Colis modifié!');
            return $this->redirect( $this->generateUrl('CitTestBundle_useractivitypage'));
        }

        //si editForm n'est pas valide on régènère la page
        return $this->render('CitTestBundle:Colis:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
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
}
