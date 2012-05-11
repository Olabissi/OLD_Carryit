<?php

namespace Cit\UserBundle\Controller;

use Cit\UserBundle\Entity\User;
use Cit\UserBundle\Form\Type\ProfileFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $current_user = $this->getUserAndCheck();

    	//récupération de l'erreur pour le topmenu
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);
        
    	return $this->render('CitUserBundle:Profile:show.html.twig',
    		array('error' => $error, 'user' => $current_user));
    }

    public function editInfosAction()
    {
        $current_user = $this->getUserAndCheck();
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);
        $form = $this->container->get('fos_user.profile.form');
        //$form = $this->createForm(new ProfileFormType($current_user));

        return $this->render('CitUserBundle:Profile:edit.html.twig', array(
                'user' => $current_user,
                'error' => $error,
                'form' => $form->createView(),
                'theme' => $this->container->getParameter('fos_user.template.theme'),
                )
        );
    }

    public function changePwdAction()
    {
        $current_user = $this->getUserAndCheck();
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);
        $userform = $this->container->get('fos_user.change_password.form')->createView();
        $theme = $this->container->getParameter('fos_user.template.theme');

        return $this->render('CitUserBundle:ChangePassword:changePassword.html.twig',
            array('user' => $current_user,
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

    protected function getUserAndCheck()
    {
        $current_user = $this->container->get('security.context')->getToken()->getUser();

        if (!is_object($current_user) || !$current_user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $current_user;
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