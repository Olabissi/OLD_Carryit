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
use FOS\UserBundle\Form\Model\CheckPassword;
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

        //on récupère l'ancien email
        $email = $user->getEmail();

        $form = $this->container->get('fos_user.profile.form');
		$formHandler = $this->container->get('fos_user.profile.form.handler');

        // On éxécute le handler pour le formulaire d'édition de profil. S'il retourne 0, le profil a bien été modifié
        $process = $formHandler->process($user);
        if (0 == $process) 
        {
            $temp = $user;
            $form->setData(new CheckPassword($temp));
            if ($temp->getEmail() != $email)
            {
                //l'adresse email a été modifiée. 
                //Il faut envoyer un email à la nouvelle adresse pour la valider
            }

            $this->container->get('session')->clearFlashes();
            $this->setFlash('fos_user_success', 'profile.flash.updated');

            return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.twig',
    		array('user' => $user,'error' => $error));
        }
        elseif (1 == $process)
        {
            $this->setFlash('warning', 'le nom d\'utilisateur doit faire au minimum 3 carractères');
        }
        elseif (2 == $process)
        {
            $this->setFlash('warning', 'le nom d\'utilisateur ne doit pas dépasser 30 caractères');
        }
        elseif (3 == $process)
        {
            $this->setFlash('warning', 'le numéro de téléphone doit faire au minimum 8 caractères');
        }
        elseif (4 == $process)
        {
            $this->setFlash('warning', 'le numéro de téléphone ne doit pas dépasser 20 caractères');
        }
        elseif (5 == $process)
        {
            $this->setFlash('warning', 'le nom ne doit pas dépasser 100 caractères');
        }
        elseif (6 == $process)
        {
            $this->setFlash('warning', 'le prénom ne doit pas dépasser 100 caractères');
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