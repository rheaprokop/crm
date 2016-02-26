<?php

/*
 * This software belongs to Rhea Software S.R.O.
 * Any other information are specified in the software contract agreement.
 */

namespace CRM\SaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="SalesOrders")
 */
class SaleOrder {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="order_reference", type="string", length=50)
     */
    protected $orderReference;

    /**
     * @ORM\Column(name="quote_id", type="string", length=50, nullable=true)
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
     * @ORM\Column(name="order_date", type="date")
     */
    protected $orderDate;

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
     * @ORM\Column(name="customer_name", type="string", length=50, nullable=true)
     */
    protected $customerName;

    /**
     * @ORM\Column(name="contact_person", type="string", length=50, nullable=true)
     */
    protected $contactPerson;
    /**
     * @ORM\Column(name="customer_street", type="string", length=50, nullable=true)
     */
    protected $customerStreet;

    /**
     * @ORM\Column(name="customer_city", type="string", length=50, nullable=true)
     */
    protected $customerCity;

    /**
     * @ORM\Column(name="customer_state", type="string", length=50, nullable=true)
     */
    protected $customerState;

    /**
     * @ORM\Column(name="customer_country", type="string", length=50, nullable=true)
     */
    protected $customerCountry;

    /**
     * @ORM\Column(name="customer_email", type="string", length=50, nullable=true)
     */
    protected $customerEmail;

    /**
     * @ORM\Column(name="customer_fax", type="string", length=50, nullable=true)
     */
    protected $customerFax;

    /**
     * @ORM\Column(name="customer_mobile", type="string", length=50, nullable=true)
     */
    protected $customerMobile;

    /**
     * @ORM\Column(name="customer_phone", type="string", length=50, nullable=true)
     */
    protected $customerPhone;

    /**
     * @ORM\Column(name="billing_name", type="string", length=50, nullable=true)
     */
    protected $billingName;

    /**
     * @ORM\Column(name="billing_street", type="string", length=50, nullable=true)
     */
    protected $billingStreet;

    /**
     * @ORM\Column(name="billing_city", type="string", length=50, nullable=true)
     */
    protected $billingCity;

    /**
     * @ORM\Column(name="billing_date", type="string", length=50, nullable=true)
     */
    protected $billingState;

    /**
     * @ORM\Column(name="billing_country", type="string", length=50, nullable=true)
     */
    protected $billingCountry;

    /**
     * @ORM\Column(name="billing_phone", type="string", length=50, nullable=true)
     */
    protected $billingPhone;

    /**
     * @ORM\Column(name="billing_mobile", type="string", length=50, nullable=true)
     */
    protected $billingMobile;

    /**
     * @ORM\Column(name="billing_fax", type="string", length=50, nullable=true)
     */
    protected $billingFax;

    /**
     * @ORM\Column(name="billing_email", type="string", length=50, nullable=true)
     */
    protected $billingEmail;

    /**
     * @ORM\Column(name="shipping_name", type="string", length=50, nullable=true)
     */
    protected $shippingName;

    /**
     * @ORM\Column(name="shipping_street", type="string", length=50, nullable=true)
     */
    protected $shippingStreet;

    /**
     * @ORM\Column(name="shipping_city", type="string", length=50, nullable=true)
     */
    protected $shippingCity;

    /**
     * @ORM\Column(name="shipping_state", type="string", length=50, nullable=true)
     */
    protected $shippingState;

    /**
     * @ORM\Column(name="shipping_country", type="string", length=50, nullable=true)
     */
    protected $shippingCountry;

    /**
     * @ORM\Column(name="shipping_phone", type="string", length=50, nullable=true)
     */
    protected $shippingPhone;

    /**
     * @ORM\Column(name="shipping_mobile", type="string", length=50, nullable=true)
     */
    protected $shippingMobile;

    /**
     * @ORM\Column(name="shipping_fax", type="string", length=50, nullable=true)
     */
    protected $shippingFax;

    /**
     * @ORM\Column(name="shipping_email", type="string", length=50, nullable=true)
     */
    protected $shippingEmail;


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
     * @ORM\Column(name="sales_rep", type="string", length=10, nullable=true)
     */
    protected $salesRep;
    
    /**
     * @var string
     *
     * @ORM\Column(name="amount_currency", type="string", length=10, nullable=true)
     */
    protected $amountCurrency;

    /**
     * @ORM\Column(name="notes", type="string", length=150, nullable=true)
     */
    protected $notes;

    /**
     * @ORM\Column(name="order_status", type="string", length=10, nullable=true)
     */
    protected $orderStatus;


    public function getId()
    {
        return $this->id;
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

    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return string
     */
    public function getOrderDate()
    {
        return $this->orderDate;
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



    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getCustomerName()
    {
        return $this->customerName;
    }

    public function setContactPerson($contactPerson)
    {
        $this->contactPerson = $contactPerson;

        return $this;
    }


    public function getContactPerson()
    {
        return $this->contactPerson;
    }



    public function setCustomerStreet($customerStreet)
    {
        $this->customerStreet = $customerStreet;

        return $this;
    }


    public function getCustomerStreet()
    {
        return $this->customerStreet;
    }


    public function setCustomerCity($customerCity)
    {
        $this->customerCity = $customerCity;

        return $this;
    }


    public function getCustomerCity()
    {
        return $this->customerCity;
    }


    public function setCustomerState($customerState)
    {
        $this->customerState = $customerState;

        return $this;
    }


    public function getCustomerState()
    {
        return $this->customerState;
    }


    public function setCustomerCountry($customerCountry)
    {
        $this->customerCountry = $customerCountry;

        return $this;
    }


    public function getCustomerCountry()
    {
        return $this->customerCountry;
    }


    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }


    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }


    public function setCustomerFax($customerFax)
    {
        $this->customerFax = $customerFax;

        return $this;
    }


    public function getCustomerFax()
    {
        return $this->customerFax;
    }


    public function setCustomerMobile($customerMobile)
    {
        $this->customerMobile = $customerMobile;

        return $this;
    }


    public function getCustomerMobile()
    {
        return $this->customerMobile;
    }


    public function setCustomerPhone($customerPhone)
    {
        $this->customerPhone = $customerPhone;

        return $this;
    }


    public function getCustomerPhone()
    {
        return $this->customerPhone;
    }


    public function setBillingName($billingName)
    {
        $this->billingName = $billingName;

        return $this;
    }


    public function getBillingName()
    {
        return $this->billingName;
    }


    public function setBillingStreet($billingStreet)
    {
        $this->billingStreet = $billingStreet;

        return $this;
    }


    public function getBillingStreet()
    {
        return $this->billingStreet;
    }


    public function setBillingCity($billingCity)
    {
        $this->billingCity = $billingCity;

        return $this;
    }


    public function getBillingCity()
    {
        return $this->billingCity;
    }


    public function setBillingState($billingState)
    {
        $this->billingState = $billingState;

        return $this;
    }


    public function getBillingState()
    {
        return $this->billingState;
    }


    public function setBillingPhone($billingPhone)
    {
        $this->billingPhone = $billingPhone;

        return $this;
    }



    public function getBillingPhone()
    {
        return $this->billingPhone;
    }


    public function setBillingMobile($billingMobile)
    {
        $this->billingMobile = $billingMobile;

        return $this;
    }



    public function getBillingMobile()
    {
        return $this->billingMobile;
    }


    public function setBillingFax($billingFax)
    {
        $this->billingFax = $billingFax;

        return $this;
    }



    public function getBillingFax()
    {
        return $this->billingFax;
    }


    public function setBillingemail($billingEmail)
    {
        $this->billingEmail = $billingEmail;

        return $this;
    }


    public function getBillingEmail()
    {
        return $this->billingEmail;
    }


    public function getBillingCountry()
    {
        return $this->billingCountry;
    }


    public function setBillingCountry($billingCountry)
    {
        $this->billingCountry = $billingCountry;

        return $this;
    }

    public function setShippingName($shippingName)
    {
        $this->shippingName = $shippingName;

        return $this;
    }


    public function getShippingName()
    {
        return $this->shippingName;
    }



    public function setShippingStreet($shippingStreet)
    {
        $this->shippingStreet = $shippingStreet;

        return $this;
    }


    public function getShippingStreet()
    {
        return $this->shippingStreet;
    }


    public function setShippingCity($shippingCity)
    {
        $this->shippingCity = $shippingCity;

        return $this;
    }


    public function getShippingCity()
    {
        return $this->shippingCity;
    }


    public function setShippingState($shippingState)
    {
        $this->shippingState = $shippingState;

        return $this;
    }


    public function getShippingState()
    {
        return $this->shippingState;
    }


    public function setShippingCountry($shippingCountry)
    {
        $this->shippingCountry = $shippingCountry;

        return $this;
    }


    public function getShippingCountry()
    {
        return $this->shippingCountry;
    }



    public function setShippingPhone($shippingPhone)
    {
        $this->shippingPhone = $shippingPhone;

        return $this;
    }


    public function getShippingPhone()
    {
        return $this->shippingPhone;
    }


    public function setShippingMobile($shippingMobile)
    {
        $this->shippingMobile = $shippingMobile;

        return $this;
    }


    public function getShippingMobile()
    {
        return $this->shippingMobile;
    }


    public function getShippingFax()
    {
        return $this->shippingFax;
    }


    public function setShippingFax($shippingFax)
    {
        $this->shippingFax = $shippingFax;

        return $this;
    }

    public function getShippingEmail()
    {
        return $this->shippingEmail;
    }

    public function setShippingEmail($shippingEmail)
    {
        $this->shippingEmail = $shippingEmail;

        return $this;
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

    public function setSalesRep($salesRep)
    {
        $this->salesRep = $salesRep;

        return $this;
    }

    public function getSalesRep()
    {
        return $this->salesRep;
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

    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    public function getNotes()
    {
        return $this->notes;
    }


    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

}
