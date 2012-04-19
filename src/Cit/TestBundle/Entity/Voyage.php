<?php

namespace Cit\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cit\UserBundle\Entity\User;

/**
 * Cit\TestBundle\Entity\Voyage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cit\TestBundle\Entity\VoyageRepository")
 */
class Voyage
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
     * @var string $aeroport_depart
     *
     * @ORM\Column(name="aeroport_depart", type="string", length=255)
     */
    private $aeroport_depart;

    /**
     * @var string $aeroport_arrivee
     *
     * @ORM\Column(name="aeroport_arrivee", type="string", length=255)
     */
    private $aeroport_arrivee;

    /**
     * @var datetime $heure_depart
     *
     * @ORM\Column(name="heure_depart", type="datetime")
     */
    private $heure_depart;

    /**
     * @var datetime $heure_arrivee
     *
     * @ORM\Column(name="heure_arrivee", type="datetime")
     */
    private $heure_arrivee;

    /**
     * @var integer $nb_kg_disponibles
     *
     * @ORM\Column(name="nb_kg_disponibles", type="integer")
     */
    private $nb_kg_disponibles;

    /**
     * @var integer $prix_par_kg
     *
     * @ORM\Column(name="prix_par_kg", type="integer")
     */
    private $prix_par_kg;

    /**
     * @var string $compagnie_air
     *
     * @ORM\Column(name="compagnie_air", type="string", length=255)
     */
    private $compagnie_air;

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
     * Set aeroport_depart
     *
     * @param string $aeroportDepart
     */
    public function setAeroportDepart($aeroportDepart)
    {
        $this->aeroport_depart = $aeroportDepart;
    }

    /**
     * Get aeroport_depart
     *
     * @return string 
     */
    public function getAeroportDepart()
    {
        return $this->aeroport_depart;
    }

    /**
     * Set aeroport_arrivee
     *
     * @param string $aeroportArrivee
     */
    public function setAeroportArrivee($aeroportArrivee)
    {
        $this->aeroport_arrivee = $aeroportArrivee;
    }

    /**
     * Get aeroport_arrivee
     *
     * @return string 
     */
    public function getAeroportArrivee()
    {
        return $this->aeroport_arrivee;
    }

    /**
     * Set heure_depart
     *
     * @param datetime $heureDepart
     */
    public function setHeureDepart($heureDepart)
    {
        $this->heure_depart = $heureDepart;
    }

    /**
     * Get heure_depart
     *
     * @return datetime 
     */
    public function getHeureDepart()
    {
        return $this->heure_depart;
    }

    /**
     * Set heure_arrivee
     *
     * @param datetime $heureArrivee
     */
    public function setHeureArrivee($heureArrivee)
    {
        $this->heure_arrivee = $heureArrivee;
    }

    /**
     * Get heure_arrivee
     *
     * @return datetime 
     */
    public function getHeureArrivee()
    {
        return $this->heure_arrivee;
    }

    /**
     * Set nb_kg_disponibles
     *
     * @param integer $nbKgDisponibles
     */
    public function setNbKgDisponibles($nbKgDisponibles)
    {
        $this->nb_kg_disponibles = $nbKgDisponibles;
    }

    /**
     * Get nb_kg_disponibles
     *
     * @return integer 
     */
    public function getNbKgDisponibles()
    {
        return $this->nb_kg_disponibles;
    }

    /**
     * Set prix_par_kg
     *
     * @param integer $prixParKg
     */
    public function setPrixParKg($prixParKg)
    {
        $this->prix_par_kg = $prixParKg;
    }

    /**
     * Get prix_par_kg
     *
     * @return integer 
     */
    public function getPrixParKg()
    {
        return $this->prix_par_kg;
    }

    /**
     * Set compagnie_air
     *
     * @param string $compagnieAir
     */
    public function setCompagnieAir($compagnieAir)
    {
        $this->compagnie_air = $compagnieAir;
    }

    /**
     * Get compagnie_air
     *
     * @return string 
     */
    public function getCompagnieAir()
    {
        return $this->compagnie_air;
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