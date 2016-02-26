<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SupportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TicketReplyType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('reply', 'textarea', array('required' => true));

    }

    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CRM\SupportBundle\Entity\TicketReply',
            'em' => '',
        );
    }

    public function getName() {
        return 'ticketreply';
    }

}
