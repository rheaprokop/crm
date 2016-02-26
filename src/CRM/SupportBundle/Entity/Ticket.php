<?php

namespace CRM\SupportBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Tickets")
 */
class Ticket {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @var string $ticket_id
     *
     */
    protected $ticketId;

    /**
     * @ORM\Column(type="string", length=50)
     * @var string $ticket_category
     *
     */
    protected $category;

    /**
     * @ORM\Column(type="string", length=50)
     * @var string $customer_name
     *
     */
    protected $customerName;

    /**
     * @ORM\Column(type="string", length=50)
     * @var string $customer_email
     *
     */
    protected $customerEmail;

    /**
     * @ORM\Column(type="string", length=50)
     * @var string $short_desc
     *
     */
    protected $shortdesc;

    /**
     * @ORM\Column(type="string", length=150)
     * @var string $priority
     *
     */
    protected $priority;

    /**
     * @ORM\Column(type="string", length=20)
     * @var string $status
     *
     */
    protected $status;

    /**
     * @ORM\Column(type="string", length=1000)
     * @var string $long_desc
     *
     */
    protected $longdesc;

    /**
     * @ORM\Column(name="creation_date", type="datetime")
     */
    protected $creationDate;

    /**
     * @ORM\Column(name="creation_user", type="string", length=50)
     */
    protected $creationUser;

    /**
     * @ORM\ManyToOne(targetEntity="TicketCategory", inversedBy="tixlist")
     * @ORM\JoinColumn(name="category_ticket", referencedColumnName="id")
     * */
    protected $catTicketId;

    public function __construct() {
        $this->tixlist = new ArrayCollection();
    }

    public function __toString() {
        return $this->shortdesc;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTicketId() {
        return $this->ticketId;
    }

    public function setTicketId($ticketId) {
        $this->ticketId = $ticketId;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

    public function getCustomerName() {
        return $this->customerName;
    }

    public function setCustomerName($customerName) {
        $this->customerName = $customerName;
    }

    public function getCustomerEmail() {
        return $this->customerEmail;
    }

    public function setCustomerEmail($customerEmail) {
        $this->customerEmail = $customerEmail;
    }

    public function getShortDesc() {
        return $this->shortdesc;
    }

    public function setShortDesc($shortdesc) {
        $this->shortdesc = $shortdesc;
    }

    public function getLongDesc() {
        return $this->longdesc;
    }

    public function setLongDesc($longdesc) {
        $this->longdesc = $longdesc;
    }

    public function setPriority($priority) {
        $this->priority = $priority;
    }

    public function getPriority() {
        return $this->priority;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getStatus() {
        return $this->status;
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

    public function setCatTicketId($catTicketId) {
        $this->catTicketId= $catTicketId;

        return $this;
    }

    public function getCatTicketId() {
        return $this->catTicketId;
    }

}
