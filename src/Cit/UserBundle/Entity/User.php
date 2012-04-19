<?php

namespace Cit\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cit_user")
 */
class User extends BaseUser
{
	
    /* certains attributs existant dans la mapped superclass :
    
    username : nom d'utilisateur avec lequel l'utilisateur va s'identifier
    email : l'adresse email
    enabled : true ou false suivant que l'inscription de l'utilisateur a été validé ou non (dans le cas d'une confirmation par email par exemple)
    password : le mot de passe de l'utilisateur
    lastLogin : la date de la dernière connexion
    locked : si vous voulez désactiver des comptes
    expired : si vous voulez que les comptes expirent au-delà d'une certaine durée 
    
    tous les attributs : https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Resources/config/doctrine/User.orm.xml */

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $prenom
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable = true)
     */
    private $prenom;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable = true)
     */
    private $nom;

    /**
     * @var date $date_de_naissance
     *
     * @ORM\Column(name="date_de_naissance", type="date", nullable = true)
     */
    private $date_de_naissance;

    /**
     * @var string $mobilephone
     *
     * @ORM\Column(name="mobilephone", type="string", length=10, nullable = true)
     */
    private $mobilephone;

    /**
     * @var string $adresse
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable = true)
     */
    private $adresse;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set date_de_naissance
     *
     * @param date $dateDeNaissance
     */
    public function setDateDeNaissance($dateDeNaissance)
    {
        $this->date_de_naissance = $dateDeNaissance;
    }

    /**
     * Get date_livraison_souhaitee
     *
     * @return date 
     */
    public function getDateDeNaissance()
    {
        return $this->date_de_naissance;
    }

    /**
     * Set mobilephone
     *
     * @param string $mobilephone
     */
    public function setMobilephone($mobilephone)
    {
        $this->mobilephone = $mobilephone;
    }

    /**
     * Get mobilephone
     *
     * @return string 
     */
    public function getMobilephone()
    {
        return $this->mobilephone;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }
}