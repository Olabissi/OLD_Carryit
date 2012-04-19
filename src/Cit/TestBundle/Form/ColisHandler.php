<?php

namespace Cit\TestBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Doctrine\ORM\EntityManager;
use Cit\TestBundle\Entity\Colis;
use Cit\UserBundle\Entity\User;

class ColisHandler
{
    protected $form;
    protected $request;
    protected $em;

    public function __construct(Form $form, Request $request, EntityManager $em)
    {
        $this->form    = $form;
        $this->request = $request;
        $this->em      = $em;
    }

    public function process(User $current_user)
    {
        if( $this->request->getMethod() == 'POST' )
        {
            $this->form->bindRequest($this->request);

            if( $this->form->isValid() )
            {
                $this->onSuccess($this->form->getData(), $current_user);

                return true;
            }
        }

        return false;
    }

    public function onSuccess(Colis $colis, User $user)
    {
        $colis->setUser($user);
        $this->em->persist($colis);
        $this->em->flush();
    }
}