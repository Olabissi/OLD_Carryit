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
                $packet = $this->form->getData();

                $departok = $this->em->getRepository('CitTestBundle:City')->isCity($packet->getVilleDepart());
                if (!$departok)
                {
                    return 1;
                }
                $arriveeok = $this->em->getRepository('CitTestBundle:City')->isCity($packet->getVilleArrivee());
                if (!$arriveeok)
                {
                    return 2;
                }
                if ($packet->getPoidsKg() <= 0)
                {
                    return 3;
                }
                if (23 < $packet->getPoidsKg())
                {
                    return 4;
                }
                if ('Sélectionnez une catégorie'== $packet->getCategorie() )
                {
                    return 5;
                }
                $aujdui = date('Ymd');
                if ($aujdui > $packet->getDateLivraisonSouhaitee()->format('Ymd'))
                {
                    return 6;
                }
                if ($this->alreadyExists($packet, $current_user))
                {
                    return 7;
                }

                //appeler la fonction qui permet d'enregistrer le voyage dans la base de données
                $this->onSuccess($packet, $current_user);
                return 0;
            }
        }

        return 6;
    }

    public function onSuccess(Colis $colis, User $user)
    {
        $colis->setUser($user);
        $this->em->persist($colis);
        $this->em->flush();
    }

    protected function alreadyExists(Colis $colis, User $user)
    {
        $colis->setUser($user);
        $userid = $colis->getUser()->getId();
        
        $exists = $this->em->getRepository('CitTestBundle:Colis')->findBy(array(
            'user'=> $userid,
            'ville_depart' => $colis->getVilleDepart(),
            'ville_arrivee' => $colis->getVilleArrivee(),
            'date_livraison_souhaitee' => $colis->getDateLivraisonSouhaitee(),
            ));

        if (!$exists)
        {
            return false;
        }

        return true;
    }
}