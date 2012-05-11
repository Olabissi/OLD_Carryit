<?php

namespace Cit\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Cit\UserBundle\Entity\User;
use Cit\UserBundle\Form\Type\ProfileFormType;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\ProfileController as BaseController;

class ProfileController extends BaseController
{
    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

		$request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.twig', 
        	array('user' => $user, 'error' => $error));
    }

    /**
     * Edit the user
     */
    public function editAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

		if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        //calcul de l'erreur
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);

        //$form = $this->createForm(new ProfileFormType($user));
        $form = $this->container->get('fos_user.profile.form');
        $formHandler = $this->container->get('fos_user.profile.form.handler');
		
        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('fos_user_success', 'profile.flash.updated');

            return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.twig',
    		array('user' => $user,'error' => $error));
        }

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.twig',
            array(
            	'form' => $form->createView(), 
            	'error' => $error,
            	'theme' => $this->container->getParameter('fos_user.template.theme'),
            	'user' => $user
            	)
        );
    }

    protected function setFlash($action, $value)
    {
        $this->container->get('session')->setFlash($action, $value);
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