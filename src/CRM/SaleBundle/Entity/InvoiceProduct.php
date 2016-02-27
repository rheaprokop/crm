<?php

namespace CRM\SaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="InvoicesProducts")
 */
class InvoiceProduct {

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
     * @ORM\Column(name="invoice_db_id", type="string", length=20, nullable=false)
     */
    protected $invoiceDBId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="order_db_id", type="string", length=20, nullable=false)
     */
    protected $orderDBId;

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
     * @ORM\Column(type="string", name="price_quantity", length=50)
     */
    protected $quantity;
    
    /**
     * @ORM\Column(type="decimal", precision=8, scale=2, name="amount", length=50)
     */
    protected $amount;
    
    /**
     * @ORM\Column(name="creation_date", type="datetime")
     */
    protected $creationDate;
    
    /**
     * @ORM\Column(name="creation_user", type="string", length=50)
     */
    protected $creationUser;

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

    public function getInvoiceDBId() {
        return $this->invoiceDBId;
    }

    public function setInvoiceDBId($invoiceDBId) {
        $this->invoiceDBId = $invoiceDBId;

        return $this;
    }
    
    public function getOrderDBId() {
    	return $this->orderDBId;
    }
    
    public function setOrderDBId($orderDBId) {
    	$this->orderDBId = $orderDBId;
    
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
