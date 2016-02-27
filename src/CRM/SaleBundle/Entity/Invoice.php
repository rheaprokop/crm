<?php

/*
 * This software belongs to Rhea Software S.R.O.
 * Any other information are specified in the software contract agreement.
 */

namespace CRM\SaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Invoices")
 */
class Invoice {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="invoice_no", type="string", length=50)
     */
    protected $invoiceNumber;

    /**
     * @ORM\Column(name="order_reference", type="string", length=50, nullable=true)
     */
    protected $orderReference;

    /**
     * @ORM\Column(name="quote_id", type="string", length=50)
     */
    protected $quoteId;

    /**
     * @ORM\Column(name="creation_date", type="datetime")
     */
    protected $creationDate;

    /**
     * @ORM\Column(name="creation_user", type="string", length=50)
     */
    protected $creationUser;

    /**
     * @ORM\Column(name="invoice_date", type="date")
     */
    protected $invoiceDate;

    /**
     * @ORM\Column(name="po_order", type="string", length=50, nullable=true)
     */
    protected $poOrder;

    /**
     * @ORM\Column(name="customer_number", type="string", length=50, nullable=true)
     */
    protected $customerNumber;

    /**
     * @ORM\Column(name="customer_contact_id", type="string", length=50, nullable=true)
     */
    protected $customerContact_Id;

    /**
     * @ORM\Column(name="invoice_customer_name", type="string", length=50)
     */
    protected $invoiceCustomerName;

    /**
     * @ORM\Column(name="invoice_person", type="string", length=50)
     */
    protected $invoicePerson;
    /**
     * @ORM\Column(name="invoice_street", type="string", length=50, nullable=true)
     */
    protected $invoiceStreet;

    /**
     * @ORM\Column(name="invoice_city", type="string", length=50, nullable=true)
     */
    protected $invoiceCity;

    /**
     * @ORM\Column(name="invoice_state", type="string", length=50, nullable=true)
     */
    protected $invoiceState;

    /**
     * @ORM\Column(name="invoice_country", type="string", length=50, nullable=true)
     */
    protected $invoiceCountry;

    /**
     * @ORM\Column(name="invoice_email", type="string", length=50, nullable=true)
     */
    protected $invoiceEmail;

    /**
     * @ORM\Column(name="invoice_fax", type="string", length=50, nullable=true)
     */
    protected $invoiceFax;

    /**
     * @ORM\Column(name="invoice_mobile", type="string", length=50, nullable=true)
     */
    protected $invoiceMobile;

    /**
     * @ORM\Column(name="invoice_phone", type="string", length=50, nullable=true)
     */
    protected $invoicePhone;



    /**
     * @ORM\Column(name="shipping_total", type="integer", length=20, nullable=true)
     */
    protected $totalShipping;
 
    /**
     * @ORM\Column(name="shipping_method", type="string", length=5, nullable=true)
     */
    protected $shippingMethod;
    

    /**
     * @ORM\Column(name="delivery_date", type="date")
     */
    protected $deliveryDate;


    /**
     * @ORM\Column(name="discount_total", type="integer", length=10, nullable=true)
     */
    protected $totalDiscount;


    /**
     * @ORM\Column(name="vat_total", type="integer", length=10, nullable=true)
     */
    protected $totalVat;

    /**
     * @ORM\Column(name="surcharge_total", type="integer", length=20, nullable=true)
     */
    protected $totalSurcharge;

    /**
     * @ORM\Column(name="subtotal", type="integer", length=20, nullable=true)
     */
    protected $subtotal;

    /**
     * @ORM\Column(name="total_amount", type="integer", length=20, nullable=true)
     */
    protected $amountDue;
    
    /**
     * @var string
     *
     * @ORM\Column(name="amount_currency", type="string", length=10, nullable=true)
     */
    protected $amountCurrency;

    /**
     * @ORM\Column(name="sales_rep", type="string", length=10, nullable=true)
     */
    protected $salesRep;

    /**
     * @ORM\Column(name="notes", type="string", length=150, nullable=true)
     */
    protected $notes;

    /**
     * @ORM\Column(name="order_status", type="string", length=10, nullable=true)
     */
    protected $invoiceStatus;


    public function getId()
    {
        return $this->id;
    }


    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    public function setOrderReference($orderReference)
    {
        $this->orderReference = $orderReference;

        return $this;
    }

    public function getOrderReference()
    {
        return $this->orderReference;
    }

    public function setQuoteId($quoteId)
    {
        $this->quoteId = $quoteId;

        return $this;
    }

    public function getQuoteId()
    {
        return $this->quoteId;
    }


    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationUser($creationUser)
    {
        $this->creationUser = $creationUser;

        return $this;
    }

    public function getCreationUser()
    {
        return $this->creationUser;
    }

    public function setInvoiceDate($invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;

        return $this;
    }

    /**
     * Get invoiceDate
     *
     * @return string
     */
    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    public function setPoOrder($poOrder)
    {
        $this->poOrder = $poOrder;

        return $this;
    }

    public function getPoOrder()
    {
        return $this->poOrder;
    }

    public function setCustomerNumber($customerNumber)
    {
        $this->customerNumber = $customerNumber;

        return $this;
    }

    public function getCustomerNumber()
    {
        return $this->customerNumber;
    }

    public function setCustomerContactId($customerContact_Id)
    {
        $this->customerContact_Id = $customerContact_Id;

        return $this;
    }

    public function getCustomerContactId()
    {
        return $this->customerContact_Id;
    }



    public function setInvoiceCustomerName($invoiceCustomerName)
    {
        $this->invoiceCustomerName = $invoiceCustomerName;

        return $this;
    }

    public function getInvoiceCustomerName()
    {
        return $this->invoiceCustomerName;
    }

    public function setInvoicePerson($invoicePerson)
    {
        $this->invoicePerson = $invoicePerson;

        return $this;
    }


    public function getInvoicePerson()
    {
        return $this->invoicePerson;
    }



    public function setInvoiceStreet($invoiceStreet)
    {
        $this->invoiceStreet = $invoiceStreet;

        return $this;
    }


    public function getInvoiceStreet()
    {
        return $this->invoiceStreet;
    }


    public function setInvoiceCity($invoiceCity)
    {
        $this->invoiceCity = $invoiceCity;

        return $this;
    }


    public function getInvoiceCity()
    {
        return $this->invoiceCity;
    }


    public function setInvoiceState($invoiceState)
    {
        $this->invoiceState = $invoiceState;

        return $this;
    }


    public function getInvoiceState()
    {
        return $this->invoiceState;
    }


    public function setInvoiceCountry($invoiceCountry)
    {
        $this->invoiceCountry = $invoiceCountry;

        return $this;
    }


    public function getInvoiceCountry()
    {
        return $this->invoiceCountry;
    }


    public function setInvoiceEmail($invoiceEmail)
    {
        $this->invoiceEmail = $invoiceEmail;

        return $this;
    }


    public function getInvoiceEmail()
    {
        return $this->invoiceEmail;
    }


    public function setInvoiceFax($invoiceFax)
    {
        $this->invoiceFax = $invoiceFax;

        return $this;
    }


    public function getInvoiceFax()
    {
        return $this->invoiceFax;
    }


    public function setInvoiceMobile($invoiceMobile)
    {
        $this->invoiceMobile = $invoiceMobile;

        return $this;
    }


    public function getInvoiceMobile()
    {
        return $this->invoiceMobile;
    }


    public function setInvoicePhone($invoicePhone)
    {
        $this->invoicePhone = $invoicePhone;

        return $this;
    }


    public function getInvoicePhone()
    {
        return $this->invoicePhone;
    }


    public function settotalShipping($totalShipping)
    {
        $this->totalShipping = $totalShipping;

        return $this;
    }

    public function gettotalShipping()
    {
        return $this->totalShipping;
    }  

    public function setShippingMethod($shippingMethod)
    {
    	$this->shippingMethod = $shippingMethod;
    
    	return $this;
    }
    
    public function getShippingMethod()
    {
    	return $this->shippingMethod;
    }
    
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }


    public function setTotalDiscount($totalDiscount)
    {
        $this->totalDiscount = $totalDiscount;

        return $this;
    }

    public function getTotalDiscount()
    {
        return $this->totalDiscount;
    }

    public function setTotalSurcharge($totalSurcharge)
    {
        $this->totalSurcharge = $totalSurcharge;

        return $this;
    }

    public function getTotalSurcharge()
    {
        return $this->totalSurcharge;
    }

    public function setTotalVat($totalVat)
    {
        $this->totalVat = $totalVat;

        return $this;
    }

    public function getTotalVat()
    {
        return $this->totalVat;
    }

    public function setamountDue($amountDue)
    {
        $this->amountDue = $amountDue;

        return $this;
    }

    public function getamountDue()
    {
        return $this->amountDue;
    }

    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getSubtotal()
    {
        return $this->subtotal;
    }
    
    /**
     * Set amountCurrency
     *
     * @param string $amountCurrency
     * @return Quotes
     */
    public function setAmountCurrency($amountCurrency) {
    	$this->amountCurrency = $amountCurrency;
    
    	return $this;
    }
    
    /**
     * Get amountDue
     *
     * @return integer
     */
    public function getAmountCurrency() {
    	return $this->amountCurrency;
    }

    public function setSalesRep($salesRep)
    {
        $this->salesRep = $salesRep;

        return $this;
    }

    public function getSalesRep()
    {
        return $this->salesRep;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    public function getNotes()
    {
        return $this->notes;
    }


    public function setInvoiceStatus($invoiceStatus)
    {
        $this->invoiceStatus = $invoiceStatus;

        return $this;
    }

    public function getInvoiceStatus()
    {
        return $this->invoiceStatus;
    }

}
