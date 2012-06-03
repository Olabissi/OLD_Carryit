<?php

namespace Cit\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cit\UserBundle\Entity\User;
use Cit\TestBundle\Entity\City;
use Cit\TestBundle\Entity\CityRepository;

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
     * @var date $date_depart
     *
     * @ORM\Column(name="date_depart", type="date")
     */
    private $date_depart;

    /**
     * @var date $date_arrivee
     *
     * @ORM\Column(name="date_arrivee", type="date")
     */
    private $date_arrivee;

    /**
     * @var integer $nb_kg_disponibles
     *
     * @ORM\Column(name="nb_kg_disponibles", type="integer")
     */
    private $nb_kg_disponibles;

    /**
     * @var integer $prix_par_kg
     *
     * @ORM\Column(name="prix_par_kg", type="integer", nullable="true")
     */
    private $prix_par_kg;
    
    /**
     * @var string $compagnie_air
     *
     * @ORM\Column(name="compagnie_air", type="string", length=100, nullable="true")
     */
    private $compagnie_air;

    /**
     * @var string $departure_airport
     *
     * @ORM\Column(name="departure_airport", type="string", length=100, nullable="true")
     */
    private $departure_airport;

    /**
     * @var string $arrival_airport
     *
     * @ORM\Column(name="arrival_airport", type="string", length=100, nullable="true")
     */
    private $arrival_airport;

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
     * Set date_depart
     *
     * @param date $dateDepart
     */
    public function setDateDepart($dateDepart)
    {
        $this->date_depart = $dateDepart;
    }

    /**
     * Get date_depart
     *
     * @return date 
     */
    public function getDateDepart()
    {
        return $this->date_depart;
    }

    /**
     * Set date_arrivee
     *
     * @param date $dateArrivee
     */
    public function setDateArrivee($dateArrivee)
    {
        $this->date_arrivee = $dateArrivee;
    }

    /**
     * Get date_arrivee
     *
     * @return date 
     */
    public function getDateArrivee()
    {
        return $this->date_arrivee;
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

    /**
     * Set departure_airport
     *
     * @param string $departureAirport
     */
    public function setDepartureAirport($departureAirport)
    {
        $this->departure_airport = $departureAirport;
    }

    /**
     * Get departure_airport
     *
     * @return string 
     */
    public function getDepartureAirport()
    {
        return $this->departure_airport;
    }

    /**
     * Set arrival_airport
     *
     * @param string $arrivalAirport
     */
    public function setArrivalAirport($arrivalAirport)
    {
        $this->arrival_airport = $arrivalAirport;
    }

    /**
     * Get arrival_airport
     *
     * @return string 
     */
    public function getArrivalAirport()
    {
        return $this->arrival_airport;
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

    public function isDatesValid()
    {
        return ($this->getDateDepart() <= $this->getDateArrivee());
    }
}