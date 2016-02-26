<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SaleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class InvoiceType extends AbstractType {

    public function __construct($em, $id) {
        $this->em = $em;
        $this->id = $id;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('invoiceDate', 'date', array(
            'widget' => 'single_text', ));
        $builder->add('invoiceNumber', 'hidden', array('required' => false));
        $builder->add('orderReference', 'text', array('required' => false));
        $builder->add('poOrder', 'text', array('required' => false));
        $builder->add('invoiceCustomerName', 'text', array('required' => false));
        $builder->add('invoicePerson', 'text', array('required' => false));
        $builder->add('invoiceStreet', 'text', array('required' => false));
        $builder->add('invoiceCity', 'text', array('required' => false));
        $builder->add('invoiceState', 'text', array('required' => false));
        $builder->add('invoiceCountry', 'country', array(
            'label' => 'Please', 'preferred_choices' => array('US')
        ));
        $builder->add('invoiceEmail', 'email', array('required' => false));
        $builder->add('invoiceFax', 'text', array('required' => false));
        $builder->add('invoiceMobile', 'text', array('required' => false));
        $builder->add('invoicePhone', 'text', array('required' => false));
        // $builder->add('shippingTotal', 'text', array('required' => false));
//         //$builder->add('shippingMethod', 'entity', array(
//             'class' => 'CRMSaleBundle:ShippingMethod',
//             'property' => 'name',
//             'query_builder' => function(EntityRepository $er) {
//                     return $er->createQueryBuilder('e');
//                 },
//         ));
        $builder->add('deliveryDate', 'date', array(
            'widget' => 'single_text', ));
        $builder->add('salesRep', 'text', array('required' => false));
        $builder->add('notes', 'textarea', array('required' => false));

    }

    /**
     * Returns the default options/class for this form.
     * @param array $options
     * @return array The default options
     */
    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CRM\SaleBundle\Entity\Invoice',
            'em' => '',
        );
    }

    /**
     * Mandatory in Symfony2
     * Gets the unique name of this form.
     * @return string
     */
    public function getName() {
        return 'invoice';
    }

}
