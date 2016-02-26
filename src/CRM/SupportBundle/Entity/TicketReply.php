<?php

namespace CRM\SupportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="TicketsReplies")
 */
class TicketReply {

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
    protected $ticket_id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @var string $ticket_reply_id
     *
     */
    protected $ticket_reply_id;

    /**
     * @ORM\Column(type="string", length=50)
     * @var string $replytext
     *
     */
    protected $reply;

    /**
     * @ORM\Column(name="creation_date", type="datetime")
     */
    protected $creationDate;

    /**
     * @ORM\Column(name="creation_user", type="string", length=50)
     */
    protected $creationUser;


    public function __construct() {

    }

    public function __toString() {
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTicketId() {
        return $this->ticket_id;
    }

    public function setTicketId($ticket_id) {
        $this->ticket_id = $ticket_id;
    }


    public function getReply() {
        return $this->reply;
    }

    public function setReply($reply) {
        $this->reply = $reply;
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
}
