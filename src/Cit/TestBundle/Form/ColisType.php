<?php

namespace Cit\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ColisType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('Categorie')
            ->add('Poids_kg')
            ->add('date_livraison_souhaitee')
            ->add('ville_depart')
            ->add('ville_arrivee')
            ->add('NomDestinataire','text',array('required' => false))
            ->add('TelephoneDestinataire','text',array('required' => false))
            ->add('complement_info','text',array('required' => false))
        ;
    }

    public function getName()
    {
        return 'cit_testbundle_colistype';
    }
}
