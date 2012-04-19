<?php

namespace Cit\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('password')
            ->add('prenom')
            ->add('nom')
            ->add('date_de_naissance')
            ->add('mobilephone')
            ->add('adresse')
        ;
    }

    public function getName()
    {
        return 'cit_userbundle_userform';
    }
}