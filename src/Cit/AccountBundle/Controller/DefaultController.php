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
        $current_user = $this->container->get('security.context')->getToken()->getUser();

    	return $this->render('CitAccountBundle:Default:index.html.twig',
    		array('name' => $name, 'error' => $error, 'user' => $current_user));
    }

    public function editInfosAction()
    {
        $name = $this->getUsernm();
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error = $this->getError($request, $session);
        $form = $this->container->get('fos_user.profile.form');

        return $this->render('CitAccountBundle:Default:editmyinfos.html.twig', array(
                'name' => $name,
                'error' => $error,
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
