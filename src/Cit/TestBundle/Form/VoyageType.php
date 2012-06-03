<?php

namespace Cit\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Cit\TestBundle\Entity\City;

class VoyageType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('ville_depart', 'text', array(
                'label' => 'ville de départ',
                'attr' => array(
                    'class' => 'city'),
                ))

            ->add('departure_airport','text',array(
                'required' => false,
                'label' => 'aéroport de depart'))

            ->add('ville_arrivee', 'text', array(
                'label' => 'ville d\'arrivée',
                'attr' => array(
                    'class' => 'city'),
                ))

            ->add('arrival_airport','text',array(
                'required' => false,
                'label' => 'aéroport d\'arrivée'))

            ->add('date_depart','date',array(
                'label' => 'date de départ'))

            ->add('date_arrivee','date',array(
                'label' => 'date d\'arrivée'))

            ->add('nb_kg_disponibles','integer',array(
                'label' => 'nombre de kilos disponibles'))

            ->add('prix_par_kg','integer',array(
                'label' => 'prix proposé(en € par kilo)',
                'required' => false))

            ->add('compagnie_air','text',array(
                'label' => 'compagnie aérienne',
                'required' => false))
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
