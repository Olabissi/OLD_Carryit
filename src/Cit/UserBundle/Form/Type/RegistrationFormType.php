<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cit\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
    	parent::buildForm($builder, $options);

        //$builder
            //->add('identifiant','text')
            //->add('email', 'email', array('label'=>'adresse email'))
            //->add('plainPassword', 'repeated', array('type' => 'password'), array('label' => 'mot de passe'));
    }

    //public function getDefaultOptions(array $options)
    //{
        //return $options + array(
            //'data_class' => $this->class,
            //'intention'  => 'registration',
        //);
    //}

    //public function getName()
    //{
        //return 'cit_user_registration';
    //}
}