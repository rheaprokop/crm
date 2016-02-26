<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SupportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class TicketType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('creationUser', 'text', array('required' => true));
        $builder->add('customerName', 'text', array('required' => true));
        $builder->add('customerEmail', 'text', array('required' => true));
        $builder->add('category', 'entity', array(
            'class' => 'CRMSupportBundle:TicketCategory',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('e');
                },
        ));
        $builder->add('longdesc', 'textarea', array('required' => true));
        $builder->add('shortdesc', 'textarea', array('required' => true));
        $builder->add('priority', 'choice', array(
            'choices' => array(
                'High' => 'High',
                'Medium' => 'Medium',
                'Low' => 'Low'
            ),
            'required' => true,
            'empty_data' => ''
        ));

    }

    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CRM\ContactBundle\Entity\Ticket',
            'em' => '',
        );
    }

    public function getName() {
        return 'ticket';
    }

}
