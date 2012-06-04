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
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'FOS\UserBundle\Form\Model\CheckPassword',
            'intention'  => 'profile',
        );
    }

    protected function buildUserForm(FormBuilder $builder, array $options)
    {
        //On reprend les 2 premiers champs :
        $builder->add('username','text');
        $builder->add('email','email');

        //On ajoute nos champs :
        $builder->add('mobilephone','text',array('label' => 'telephone'));
        $builder->add('prenom','text',array('required' => false,'label' => 'prenom'));
        $builder->add('nom','text',array('required' => false,'label' => 'nom'));
        //$builder->add('adresse','text');
        //$builder->add('datedenaissance','birthday',array('data'=> null,'required' => false));
        
    }

    public function getName()
    {
        return 'cit_user_profile';
    }
}