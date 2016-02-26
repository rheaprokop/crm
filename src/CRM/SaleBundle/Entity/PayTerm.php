<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="PayTerms")
 */
class PayTerm {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO") 
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(name="abbreviation", type="string", length=5, nullable=true)
     */
    protected $abbrev;

    /**
     * @ORM\Column(name="description", type="string", length=50, nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(name="creation_date", type="datetime")
     */
    protected $creationDate;

    /**
     * @ORM\Column(name="creation_user", type="string", length=50)
     */
    protected $creationUser;

    // Getters and Setters

    public function __toString() {
        return $this->name;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setAbbrev($abbrev) {
        $this->abbrev = $abbrev;
    }

    public function getAbbrev() {
        return $this->abbrev;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function setCreationUser($creationUser) {
        $this->creationUser = $creationUser;

        return $this;
    }

    public function getCreationUser() {
        return $this->creationUser;
    }

}
