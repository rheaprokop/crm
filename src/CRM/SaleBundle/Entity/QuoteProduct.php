<?php

namespace CRM\SaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="QuotesProducts")
 */
class QuoteProduct {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="quote_db_id", type="string", length=20, nullable=false)
     */
    protected $quoteDBId;

    /**
     * @var string
     *
     * @ORM\Column(name="quote_id", type="string", length=20, nullable=false)
     */
    protected $quoteId;

    /**
     * @ORM\Column(name="product_id", type="string", length=50, nullable=true)
     */
    protected $productId;

    /**
     * @ORM\Column(name="productcode", type="string", length=50, nullable=true)
     */
    protected $productcode;

    /**
     * @ORM\Column(name="product_name", type="string", length=50, nullable=true)
     */
    protected $productname;

    /**
     * @ORM\Column(type="integer", name="product_price", length=50)
     */
    protected $productprice;

    /**
     * @ORM\Column(type="string", name="price_type", length=50)
     */
    protected $pricetype;

    /**
     * @ORM\Column(type="string", name="price_discount", length=50)
     */
    protected $discount;

    /**
     * @ORM\Column(type="string", name="price_surcharge", length=50)
     */
    protected $surcharge;

    /**
     * @ORM\Column(name="creation_date", type="datetime")
     */
    protected $createdDate;

    /**
     *
     * @ORM\Column(name="creationUser", type="string", length=50, nullable=true)
     */
    protected $creationUser;

    /**
     * @ORM\Column(type="string", name="price_quantity", length=50)
     */
    protected $quantity;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2, name="amount", length=50)
     */
    protected $amount;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    public function setQuoteId($quoteId) {
        $this->quoteId = $quoteId;

        return $this;
    }

    public function getQuoteId() {
        return $this->quoteId;
    }

    public function getQuoteDBId() {
        return $this->quoteDBId;
    }

    public function setQuoteDBId($quoteDBId) {
        $this->quoteDBId = $quoteDBId;

        return $this;
    }

    public function setProductId($productId) {
        $this->productId = $productId;

        return $this;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function setProductCode($productcode) {
        $this->productcode = $productcode;
    }

    public function getProductCode() {
        return $this->productcode;
    }

    public function setProductName($productname) {
        $this->productname = $productname;

        return $this;
    }

    public function getProductName() {
        return $this->productname;
    }

    public function setProductPrice($productprice) {
        $this->productprice = $productprice;

        return $this;
    }

    public function getProductPrice() {
        return $this->productprice;
    }

    public function setPricetype($pricetype) {
        $this->pricetype = $pricetype;

        return $this;
    }

    public function getPricetype() {
        return $this->pricetype;
    }

    public function setDiscount($discount) {
        $this->discount = $discount;

        return $this;
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function setSurcharge($surcharge) {
        $this->surcharge = $surcharge;

        return $this;
    }

    public function getSurcharge() {
        return $this->surcharge;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setCreationDate($createdDate) {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getCreationDate() {
        return $this->createdDate;
    }

    public function setCreationUser($creationUser) {
        $this->creationUser = $creationUser;
    }

    public function getCreationUser() {
        return $this->creationUser;
    }

}
