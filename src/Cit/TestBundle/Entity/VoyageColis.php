<?php

namespace Cit\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cit\TestBundle\Entity\VoyageColis
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cit\TestBundle\Entity\VoyageColisRepository")
 */
class VoyageColis
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
     * @var integer $remuneration_transporteur
     *
     * @ORM\Column(name="remuneration_transporteur", type="integer")
     */
    private $remuneration_transporteur;

    /**
     * @var string $etat
     *
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Cit\TestBundle\Entity\Voyage")
     */
    private $voyage;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Cit\TestBundle\Entity\Colis")
     */
    private $colis;


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
     * Set remuneration_transporteur
     *
     * @param integer $remunerationTransporteur
     */
    public function setRemunerationTransporteur($remunerationTransporteur)
    {
        $this->remuneration_transporteur = $remunerationTransporteur;
    }

    /**
     * Get remuneration_transporteur
     *
     * @return integer 
     */
    public function getRemunerationTransporteur()
    {
        return $this->remuneration_transporteur;
    }

    /**
     * Set etat
     *
     * @param string $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
     * Get etat
     *
     * @return string 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Get voyage
     *
     * @return Cit\TestBundle\Entity\Voyage 
     */
    public function getVoyage()
    {
        return $this->voyage;
    }

    /**
     * Set voyage
     *
     * @param Cit\TestBundle\Entity\Voyage $voyage
     */
    public function setVoyage(Cit\TestBundle\Entity\Voyage $voyage)
    {
        $this->voyage = $voyage;
    }

     /**
     * Get colis
     *
     * @return Cit\TestBundle\Entity\Colis 
     */
    public function getColis()
    {
        return $this->colis;
    }

    /**
     * Set colis
     *
     * @param Cit\TestBundle\Entity\Colis $colis
     */
    public function setColis(Cit\TestBundle\Entity\Colis $colis)
    {
        $this->colis = $colis;
    }
}