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
            //'data_class' => 'Cit\UserBundle\Entity\User',
            //'data_class' => $this->class,
            'data_class' => 'FOS\UserBundle\Form\Model\CheckPassword',
            'intention'  => 'profile',
        );
    }

    protected function buildUserForm(FormBuilder $builder, array $options)
    {
        //On reprend les 2 premiers champs :
        //$builder->add('username','text',array('data'=> $this->class->getUsername()));
        //$builder->add('email','email',array('data'=> $this->class->getEmail()));
        $builder->add('username','text');
        $builder->add('email','email');

        //On ajoute nos champs :
        //$builder->add('prenom','text',array('data'=> $this->class->getPrenom(),'required' => false));
        //$builder->add('nom','text',array('data'=> $this->class->getNom(),'required' => false));
        //$builder->add('datedenaissance','birthday',array('data'=> $this->class->getDateDeNaissance(),'required' => false));
        //$builder->add('mobilephone','text',array('data'=> $this->class->getMobilephone(),'required' => false));
        //$builder->add('adresse','text',array('data'=> $this->class->getAdresse(),'required' => false));
        $builder->add('prenom','text',array('required' => false));
        $builder->add('nom','text',array('required' => false));
        $builder->add('datedenaissance','birthday',array('data'=> null,'required' => false));
        $builder->add('mobilephone','text',array('required' => false));
        $builder->add('adresse','text',array('required' => false));
    }

    public function getName()
    {
        return 'cit_user_profile';
    }
}