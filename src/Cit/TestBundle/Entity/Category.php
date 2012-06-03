<?php

namespace Cit\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cit\TestBundle\Entity\Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cit\TestBundle\Entity\CategoryRepository")
 */
class Category
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
     * @var string $frenchname
     *
     * @ORM\Column(name="frenchname", type="string", length=255)
     */
    private $frenchname;

    /**
     * @var string $englishname
     *
     * @ORM\Column(name="englishname", type="string", length=255)
     */
    private $englishname;


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
     * Set frenchname
     *
     * @param string $name
     */
    public function setNameFrench($name)
    {
        $this->frenchname = $name;
    }

    /**
     * Get frenchname
     *
     * @return string 
     */
    public function getNameFrench()
    {
        return $this->frenchname;
    }

    /**
     * Set englishname
     *
     * @param string $name
     */
    public function setNameEnglish($name)
    {
        $this->englishname = $name;
    }

    /**
     * Get englishname
     *
     * @return string 
     */
    public function getNameEnglish()
    {
        return $this->englishname;
    }

    public function __toString() 
    { 
        //$locale = $this->get('session')->getLocale();
        return $this->frenchname;
    }
}