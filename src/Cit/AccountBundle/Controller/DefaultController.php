<?php

namespace Cit\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cit\UserBundle\Entity\User;
use Cit\UserBundle\Resources\views;
use Cit\UserBundle\Form\Type\ProfileFormType;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	//récupération des paramètres pour le topmenu
    	$name = $this->getUsernm();
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

    	return $this->render('CitAccountBundle:Default:index.html.twig',
    		array('name' => $name,'error' => $error));
    }

    public function editInfosAction()
    {
        $name = $this->getUsernm();

        //On récupère l'utilisateur connecté
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        //À partir du formBuilder, on génère le formulaire.
        //$user = 'User'; //nom de la classe
        //$form = $this->createForm(new ProfileFormType(), $current_user);

        $form = $this->container->get('fos_user.profile.form');

        $formHandler = $this->container->get('fos_user.profile.form.handler');
        $process = $formHandler->process($current_user);
        if ($process) {
            $this->setFlash('fos_user_success', 'profile.flash.updated');
            return $this->redirect( $this->generateUrl('CitAccountBundle_homepage'));
        }

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.twig', array(
                'form' => $form->createView(),
                'theme' => $this->container->getParameter('fos_user.template.theme')
                )
        );
    }

    public function updateInfosAction()
    {

    }

    public function changePwdAction()
    {
        $name = $this->getUsernm();
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);
        $userform = $this->container->get('fos_user.change_password.form')->createView();
        $theme = $this->container->getParameter('fos_user.template.theme');

        /*$form = $this->container->get('fos_user.change_password.form');
        $formHandler = $this->container->get('fos_user.change_password.form.handler');

        $current_user = $this->container->get('security.context')->getToken()->getUser();
        $process = $formHandler->process($current_user);
        if ($process) {
            $this->container->get('session')->setFlash('Succès', 'Mot de passe modifié');
            return $this->redirect( $this->generateUrl('CitAccountBundle_homepage'));
        }*/

        return $this->render('CitAccountBundle:Default:mypwd.html.twig',
            array('name' => $name,
                  'error' => $error,
                  'form' => $userform,
                  'theme' => $theme,
                  ));
    }

    protected function getUsernm()
    {
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        if (!is_object($current_user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $name = $current_user->getUsername();
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
