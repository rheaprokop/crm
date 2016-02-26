<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ProductActivities")
 */
class ProductActivity {

    /**
     * @var integer
     *
     * @ORM\Column(name="activity_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="creation_date", type="datetime")
     */
    protected $dateAdded;

    /**
     * @ORM\Column(name="product_id", type="string", length=250)
     */
    protected $productId;

    /**
     * @ORM\Column(name="creation_user", type="string", length=250)
     */
    protected $activityUser;

    /**
     * @ORM\Column(type="string", name="activity_desc", length=250)
     */
    protected $activityDesc;

    // Getters and Setters

    public function __toString() {
        return $this->actiivty;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

        public function setProductId($productId) {
        $this->productId = $productId;
    }

    public function getProductId() {
        return $this->productId;
    }
    
    public function setDateAdded($dateAdded) {
        $this->dateAdded = $dateAdded;
    }

    public function getDateAdded() {
        return $this->dateAdded;
    }

    public function getActivityDesc() {
        return $this->activityDesc;
    }

    public function setActivityDesc($activityDesc) {
        $this->activityDesc = $activityDesc;
    }

    public function setActivityUser($activityUser) {
        $this->activityUser = $activityUser;
    }

    public function getActivityUser() {
        return $this->activityUser;
    }

}
