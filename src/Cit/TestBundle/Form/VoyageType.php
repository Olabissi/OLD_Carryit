<?php

namespace Cit\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class VoyageType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('aeroport_depart')
            ->add('aeroport_arrivee')
            ->add('heure_depart')
            ->add('heure_arrivee')
            ->add('nb_kg_disponibles')
            ->add('prix_par_kg')
            ->add('compagnie_air')
            //->add('user') 
        ;
    }

    public function getName()
    {
        return 'cit_testbundle_voyagetype';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Cit\TestBundle\Entity\Voyage',
        );
    }
}
