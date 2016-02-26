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
 * @ORM\Table(name="ShippingMethods")
 */
class ShippingMethod {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=50)
     */
    protected $name;

    /**
     * @ORM\Column(name="description", type="string", length=50, nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(name="shipping_amount", type="integer")
     */
    protected $amount;

    /**
     * @ORM\Column(name="paytype", type="string", length=50, nullable=true)
     */
    protected $pricetype;

    /**
     * @ORM\Column(name="creation_date", type="datetime")
     */
    protected $creationDate;

    /**
     *
     * @ORM\Column(name="creation_user", type="string", length=50, nullable=true)
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

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function setCreationUser($creationUser) {
        $this->creationUser = $creationUser;
    }

    public function getCreationUser() {
        return $this->creationUser;
    }

    public function setPriceType($pricetype) {
        $this->pricetype = $pricetype;
    }

    public function getPriceType() {
        return $this->pricetype;
    }

}
