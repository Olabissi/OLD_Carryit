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
use Cit\UserBundle\Entity\User;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    protected function buildUserForm(FormBuilder $builder, array $options)
    {
        parent::buildUserForm($builder, $options);

        // On ajoute nos champs :
        $builder->add('prenom');
        $builder->add('nom');
        $builder->add('datedenaissance');
        $builder->add('mobilephone');
        $builder->add('adresse');
    }

    public function getName()
    {
        return 'cit_user_profile';
    }
}