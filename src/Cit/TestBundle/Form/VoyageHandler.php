<?php

namespace Cit\TestBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Doctrine\ORM\EntityManager;
use Cit\TestBundle\Entity\Voyage;
use Cit\UserBundle\Entity\User;

class VoyageHandler
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
                $trip = $this->form->getData();
                
                if ($this->alreadyExists($trip))
                {
                    return 8;
                }
                
                $departok = $this->em->getRepository('CitTestBundle:City')->isCity($trip->getVilleDepart());
                $arriveeok = $this->em->getRepository('CitTestBundle:City')->isCity($trip->getVilleArrivee());
                $aujdui = date('Ymd');

                if (!$departok)
                {
                    return 1;
                }
                if (!$arriveeok)
                {
                    return 2;
                }
                if ($aujdui > $trip->getDateDepart()->format('Ymd'))
                {
                    return 3;
                }
                if ($trip->getDateDepart() > $trip->getDateArrivee())
                {
                    return 4;
                }
                if (0 > $trip->getNbKgDisponibles())
                {
                    return 5;
                }
                if (50 < $trip->getNbKgDisponibles())
                {
                    return 6;
                }
                if ($trip->getPrixParKg() < 0)
                {
                    return 7;
                }

                //appeler la fonction qui permet d'enregistrer le voyage dans la base de données
                $this->onSuccess($trip, $current_user);
                return 0;
            }
        }

        return 50;
    }

    public function onSuccess(Voyage $voyage, User $user)
    {
        $voyage->setUser($user);
        $this->em->persist($voyage);
        $this->em->flush();
    }

    protected function alreadyExists(Voyage $voyage)
    {
        var_dump($voyage->getUser());exit;
        return false;
    }
}