<?php

namespace CRM\SaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

//use CRM\SaleBundle\Entity\Composite;
/**
 * @ORM\Entity
 * @ORM\Table(name="ProductsCatList")
 * @ORM\HasLifecycleCallbacks()
 */
class ProductCatList {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="ProductCategory", inversedBy="catlist")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="catlist")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * */
    protected $product;
    protected $countcat;

    public function __construct() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

    public function getProduct() {
        return $this->product;
    }

    public function setProduct($product) {
        $this->product = $product;
    }

    public function __toString() {
        return (string) $this->category;
    }

}
