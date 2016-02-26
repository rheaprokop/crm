<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use CRM\SaleBundle\Entity\ProductCatList;

/**
 * @ORM\Entity
 * @ORM\Table(name="Products")
 */
class Product {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="creation_date", type="datetime")
     */
    protected $dateAdded;

    /**
     *
     * @ORM\Column(name="creation_user", type="string", length=50, nullable=false)
     */
    protected $creationUser;

    /**
     * @ORM\Column(name="productcode", type="string", length=50, nullable=true)
     */
    protected $productcode;

    /**
     * @ORM\Column(name="productname", type="string", length=50, nullable=true)
     */
    protected $productname;

    /**
     * @ORM\Column(name="description", type="string", length=50)
     */
    protected $description;

    /**
     * @ORM\Column(type="integer", name="price", length=50)
     */
    protected $price;

    /**
     * @ORM\Column(type="string", name="pricetype", length=50)
     * @ORM\OneToOne(targetEntity="PayTerm")
     * @ORM\JoinColumn(name="pricetype", referencedColumnName="id")
     */
    protected $pricetype;

    /**
     * @ORM\Column(type="string", name="status", length=50)
     */
    protected $status;

    /**
     * @ORM\OneToMany(targetEntity="ProductCatList", mappedBy="product", cascade={"all"})
     * */
    protected $catlist;
    protected $categories;

    public function __construct() {
        $this->catlist = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    // Getters and Setters

    public function __toString() {
        return $this->productname;
    }

    // Important 
    public function getCategory() {
        $categories = new ArrayCollection();

        foreach ($this->catlist as $c) {
            $categories[] = $c->getCategory();
        }

        return $categories;
    }

    // Important
    public function setCategory($categories) {
        foreach ($categories as $c) {
            $catlist = new ProductCatList();

            $catlist->setProduct($this);
            $catlist->setCategory($c);

            $this->addCatList($catlist);
        }
    }

    public function getProduct() {
        return $this;
    }

    public function getCatList() {
        return $this->catlist;
    }

    public function addCatList($ProductCatList) {
        $this->catlist[] = $ProductCatList;
    }

    public function removeCatList($ProductCatList) {
        return $this->catlist->removeElement($ProductCatList);
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setProductName($productname) {
        $this->productname = $productname;
    }

    public function getProductName() {
        return $this->productname;
    }

    public function setProductCode($productcode) {
        $this->productcode = $productcode;
    }

    public function getProductCode() {
        return $this->productcode;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPricetype($pricetype) {
        $this->pricetype = $pricetype;
    }

    public function getPricetype() {
        return $this->pricetype;
    }

    public function setDateAdded($dateAdded) {
        $this->dateAdded = $dateAdded;
    }

    public function getDateAdded() {
        return $this->dateAdded;
    }

    public function setCreationUser($creationUser) {
        $this->creationUser = $creationUser;
    }

    public function getCreationUser() {
        return $this->creationUser;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getStatus() {
        return $this->status;
    }

}
