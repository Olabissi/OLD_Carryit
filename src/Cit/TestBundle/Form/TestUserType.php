<?php

namespace Cit\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TestUserType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('pseudo')
            ->add('mdp')
            ->add('prenom')
            ->add('nom')
            ->add('date_de_naissance')
            ->add('mobilephone')
            ->add('email')
            ->add('adresse')
        ;
    }

    public function getName()
    {
        return 'cit_testbundle_testusertype';
    }
}
