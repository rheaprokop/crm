<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SaleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface; 
class PayTermType extends AbstractType {

    /**
     * Builds the AddProduct form
     * @param  \Symfony\Component\Form\FormBuilder $builder
     * @param  array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', 'text', array('required' => true));  
        $builder->add('abbrev', 'text', array('required' => true));   
    }

    /**
     * Returns the default options/class for this form.
     * @param array $options
     * @return array The default options
     */
    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CRM\SaleBundle\Entity\PayTerm',
            'em' => '',
        );
    }

    /**
     * Mandatory in Symfony2
     * Gets the unique name of this form.
     * @return string
     */
    public function getName() {
        return 'name';
    }

}
