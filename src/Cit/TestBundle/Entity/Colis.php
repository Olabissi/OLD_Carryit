<?php

namespace Cit\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cit\UserBundle\Entity\User;

/**
 * Cit\TestBundle\Entity\Colis
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cit\TestBundle\Entity\ColisRepository")
 */
class Colis
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $Categorie
     *
     * @ORM\Column(name="Categorie", type="string", length=255)
     */
    private $Categorie;

    /**
     * @var integer $Poids_kg
     *
     * @ORM\Column(name="Poids_kg", type="integer")
     */
    private $Poids_kg;

    /**
     * @var date $date_livraison_souhaitee
     *
     * @ORM\Column(name="date_livraison_souhaitee", type="date")
     */
    private $date_livraison_souhaitee;

    /**
     * @var string $ville_depart
     *
     * @ORM\Column(name="ville_depart", type="string", length=255)
     */
    private $ville_depart;

    /**
     * @var string $ville_arrivee
     *
     * @ORM\Column(name="ville_arrivee", type="string", length=255)
     */
    private $ville_arrivee;

    /**
     * @var string $NomDestinataire
     *
     * @ORM\Column(name="NomDestinataire", type="string", length=255)
     */
    private $NomDestinataire;

    /**
     * @var string $TelephoneDestinataire
     *
     * @ORM\Column(name="TelephoneDestinataire", type="string", length=255)
     */
    private $TelephoneDestinataire;

    /**
     * @var string $complement_info
     *
     * @ORM\Column(name="complement_info", type="string", length=255)
     */
    private $complement_info;

    /**
     * @ORM\ManyToOne(targetEntity="Cit\UserBundle\Entity\User")
     */
    private $user;


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
     * Set Categorie
     *
     * @param string $categorie
     */
    public function setCategorie($categorie)
    {
        $this->Categorie = $categorie;
    }

    /**
     * Get Categorie
     *
     * @return string 
     */
    public function getCategorie()
    {
        return $this->Categorie;
    }

    /**
     * Set Poids_kg
     *
     * @param integer $poidsKg
     */
    public function setPoidsKg($poidsKg)
    {
        $this->Poids_kg = $poidsKg;
    }

    /**
     * Get Poids_kg
     *
     * @return integer 
     */
    public function getPoidsKg()
    {
        return $this->Poids_kg;
    }

    /**
     * Set date_livraison_souhaitee
     *
     * @param date $dateLivraisonSouhaitee
     */
    public function setDateLivraisonSouhaitee($dateLivraisonSouhaitee)
    {
        $this->date_livraison_souhaitee = $dateLivraisonSouhaitee;
    }

    /**
     * Get date_livraison_souhaitee
     *
     * @return date 
     */
    public function getDateLivraisonSouhaitee()
    {
        return $this->date_livraison_souhaitee;
    }

    /**
     * Set ville_depart
     *
     * @param string $villeDepart
     */
    public function setVilleDepart($villeDepart)
    {
        $this->ville_depart = $villeDepart;
    }

    /**
     * Get ville_depart
     *
     * @return string 
     */
    public function getVilleDepart()
    {
        return $this->ville_depart;
    }

    /**
     * Set ville_arrivee
     *
     * @param string $villeArrivee
     */
    public function setVilleArrivee($villeArrivee)
    {
        $this->ville_arrivee = $villeArrivee;
    }

    /**
     * Get ville_arrivee
     *
     * @return string 
     */
    public function getVilleArrivee()
    {
        return $this->ville_arrivee;
    }

    /**
     * Set NomDestinataire
     *
     * @param string $nomDestinataire
     */
    public function setNomDestinataire($nomDestinataire)
    {
        $this->NomDestinataire = $nomDestinataire;
    }

    /**
     * Get NomDestinataire
     *
     * @return string 
     */
    public function getNomDestinataire()
    {
        return $this->NomDestinataire;
    }

    /**
     * Set TelephoneDestinataire
     *
     * @param string $telephoneDestinataire
     */
    public function setTelephoneDestinataire($telephoneDestinataire)
    {
        $this->TelephoneDestinataire = $telephoneDestinataire;
    }

    /**
     * Get TelephoneDestinataire
     *
     * @return string 
     */
    public function getTelephoneDestinataire()
    {
        return $this->TelephoneDestinataire;
    }

    /**
     * Set complement_info
     *
     * @param string $complement_info
     */
    public function setComplementInfo($complement_info)
    {
        $this->complement_info = $complement_info;
    }

    /**
     * Get complement_info
     *
     * @return string 
     */
    public function getComplementInfo()
    {
        return $this->complement_info;
    }

    public function getUser()
    {
        return $this->user;
    }

    // Ici, on force le type de l'argument à être une instance de notre entité User.
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}