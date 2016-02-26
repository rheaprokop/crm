<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SaleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class InvoiceAmountType extends AbstractType {


    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('quoteid', 'hidden', array('required' => true));
        $builder->add('totalDiscount', 'hidden', array('read_only' => false, 'required' => true));
        $builder->add('totalSurcharge', 'hidden', array('read_only' => false, 'required' => true));
        $builder->add('totalShipping', 'text', array('read_only' => false, 'required' => true));
        $builder->add('totalVat', 'text',  array('read_only' => false, 'required' => true));
        $builder->add('subtotal', 'text',  array('read_only' => false, 'required' => true));
        $builder->add('amountDue', 'text',  array('read_only' => false, 'required' => true));
        $builder->add('invoiceStatus', 'hidden');
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
