<?php

namespace CRM\SaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Quotes")
 */
class Quote {

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
     * @ORM\Column(name="quote_id", type="string", length=20, nullable=false)
     */
    protected $quoteId;

    /**
     * @var string
     *
     * @ORM\Column(name="quote_name", type="string", length=50, nullable=true)
     */
    protected $quoteName;

    /**
     * @var string
     *
     * @ORM\Column(name="quote_send_to", type="string", length=50, nullable=true)
     */
    protected $quoteSendTo;

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
     * @ORM\Column(name="expiration_date", type="date")
     */
    protected $expirationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="account_manager", type="string", length=50, nullable=true)
     */
    protected $accountManager;

    /**
     * @var string
     *
     * @ORM\Column(name="account_name", type="string", length=50, nullable=true)
     */
    protected $accountName;

    /**
     * @var string
     *
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
     * @var string
     *
     * @ORM\Column(name="contact_phone", type="string", length=20, nullable=true)
     */
    protected $contactPhone;

    /**
     * @ORM\Column(name="customer_fax", type="string", length=50, nullable=true)
     */
    protected $customerFax;

    /**
     * @ORM\Column(name="customer_mobile", type="string", length=50, nullable=true)
     */
    protected $customerMobile;
    
    /**
     * @var string
     *
     * @ORM\Column(name="contact_email", type="string", length=50, nullable=true)
     */
    protected $contactEmail;
 
    /**
     * @var string
     *
     * @ORM\Column(name="total_discount", type="integer", length=10, nullable=true)
     */
    protected $totalDiscount;

    /**
     * @var string
     *
     * @ORM\Column(name="total_surcharge", type="integer", length=10, nullable=true)
     */
    protected $totalSurcharge;

    /**
     * @var string
     *
     * @ORM\Column(name="total_shipping", type="integer", length=10, nullable=true)
     */
    protected $totalShipping;

    /**
     * @var string
     *
     * @ORM\Column(name="total_vat", type="integer", length=10, nullable=true)
     */
    protected $totalVat;

    /**
     * @var string
     *
     * @ORM\Column(name="subtotal", type="integer", length=10, nullable=true)
     */
    protected $subtotal;

    /**
     * @var string
     *
     * @ORM\Column(name="amount_due", type="integer", length=10, nullable=true)
     */
    protected $amountDue;
    
    /**
     * @var string
     *
     * @ORM\Column(name="amount_currency", type="string", length=10, nullable=true)
     */
    protected $amountCurrency;

    /**
     * @var string
     *
     * @ORM\Column(name="additional_notes", type="string", length=50, nullable=true)
     */
    protected $additionalNotes;

    /**
     * @var string
     *
     * @ORM\Column(name="quote_status", type="string", length=10, nullable=true)
     */
    protected $quoteStatus;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set quoteId
     *
     * @param string $quoteId
     * @return Quotes
     */
    public function setQuoteId($quoteId) {
        $this->quoteId = $quoteId;

        return $this;
    }

    /**
     * Get quoteId
     *
     * @return string 
     */
    public function getQuoteId() {
        return $this->quoteId;
    }

    /**
     * Set quoteName
     *
     * @param string $quoteName
     * @return Quotes
     */
    public function setQuoteName($quoteName) {
        $this->quoteName = $quoteName;

        return $this;
    }

    /**
     * Get quoteId
     *
     * @return string 
     */
    public function getQuoteName() {
        return $this->quoteName;
    }

    public function setQuoteSendTo($quoteSendTo) {
        $this->quoteSendTo = $quoteSendTo;

        return $this;
    }

    public function getQuoteSendTo() {
        return $this->quoteSendTo;
    }
 
    public function setCreatedDate($createdDate) {
        $this->createdDate = $createdDate;

        return $this;
    }
 
    public function getCreatedDate() {
        return $this->createdDate;
    }

    public function setCreationUser($creationUser) {
        $this->creationUser = $creationUser;
    }

    public function getCreationUser() {
        return $this->creationUser;
    }

    public function setExpirationDate($expirationDate) {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getExpirationDate() {
        return $this->expirationDate;
    }

    /**
     * Set accountManager
     *
     * @param string $accountManager
     * @return Quotes
     */
    public function setAccountManager($accountManager) {
        $this->accountManager = $accountManager;

        return $this;
    }

    /**
     * Get accountManager
     *
     * @return string 
     */
    public function getAccountManager() {
        return $this->accountManager;
    }

    /**
     * Set accountName
     *
     * @param string $accountName
     * @return Quotes
     */
    public function setAccountName($accountName) {
        $this->accountName = $accountName;

        return $this;
    }

    /**
     * Get accountName
     *
     * @return string 
     */
    public function getAccountName() {
        return $this->accountName;
    }

    /**
     * Set contactPerson
     *
     * @param string $contactPerson
     * @return Quotes
     */
    public function setContactPerson($contactPerson) {
        $this->contactPerson = $contactPerson;

        return $this;
    }

    /**
     * Get contactPerson
     *
     * @return string 
     */
    public function getContactPerson() {
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
    /**
     * Set contactPhone
     *
     * @param string $contactPhone
     * @return Quotes
     */
    public function setContactPhone($contactPhone) {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    /**
     * Get contactPhone
     *
     * @return string 
     */
    public function getContactPhone() {
        return $this->contactPhone;
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
    
    /**
     * Set contactEmail
     *
     * @param string $contactEmail
     * @return Quotes
     */
    public function setContactEmail($contactEmail) {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    /**
     * Get contactEmail
     *
     * @return string 
     */
    public function getContactEmail() {
        return $this->contactEmail;
    }
 
    /**
     * Set totalDiscount
     *
     * @param integer $totalDiscount
     * @return Quotes
     */
    public function setTotalDiscount($totalDiscount) {
        $this->totalDiscount = $totalDiscount;

        return $this;
    }

    /**
     * Get totalDiscount
     *
     * @return integer 
     */
    public function getTotalDiscount() {
        return $this->totalDiscount;
    }

    /**
     * Set totalSurcharge
     *
     * @param integer $totalSurcharge
     * @return Quotes
     */
    public function setTotalSurcharge($totalSurcharge) {
        $this->totalSurcharge = $totalSurcharge;

        return $this;
    }

    /**
     * Get totalSurcharge
     *
     * @return integer 
     */
    public function getTotalSurcharge() {
        return $this->totalSurcharge;
    }

    /**
     * Set totalShipping
     *
     * @param integer $totalShipping
     * @return Quotes
     */
    public function setTotalShipping($totalShipping) {
        $this->totalShipping = $totalShipping;

        return $this;
    }

    /**
     * Get totalShipping
     *
     * @return integer 
     */
    public function getTotalShipping() {
        return $this->totalShipping;
    }

    /**
     * Set totalVat
     *
     * @param integer $totalVat
     * @return Quotes
     */
    public function setTotalVat($totalVat) {
        $this->totalVat = $totalVat;

        return $this;
    }

    /**
     * Get totalVat
     *
     * @return integer 
     */
    public function getTotalVat() {
        return $this->totalVat;
    }

    /**
     * Set subtotal
     *
     * @param integer $subtotal
     * @return Quotes
     */
    public function setSubtotal($subtotal) {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get subtotal
     *
     * @return integer 
     */
    public function getSubtotal() {
        return $this->subtotal;
    }

    /**
     * Set amountDue
     *
     * @param integer $amountDue
     * @return Quotes
     */
    public function setAmountDue($amountDue) {
        $this->amountDue = $amountDue;

        return $this;
    }

    /**
     * Get amountDue
     *
     * @return integer 
     */
    public function getAmountDue() {
        return $this->amountDue;
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

    /**
     * Set additionalNotes
     *
     * @param string $additionalNotes
     * @return Quotes
     */
    public function setAdditionalNotes($additionalNotes) {
        $this->additionalNotes = $additionalNotes;

        return $this;
    }

    /**
     * Get additionalNotes
     *
     * @return string 
     */
    public function getAdditionalNotes() {
        return $this->additionalNotes;
    }

    public function setQuoteStatus($quoteStatus) {
        $this->quoteStatus = $quoteStatus;

        return $this;
    }

    public function getQuoteStatus() {
        return $this->quoteStatus;
    }

}
