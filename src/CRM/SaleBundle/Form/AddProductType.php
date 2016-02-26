<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SaleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AddProductType extends AbstractType {

    /**
     * Builds the AddProduct form
     * @param  \Symfony\Component\Form\FormBuilder $builder
     * @param  array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options) { 
        
//        $builder->add('pricetype', 'choice', array(
//            'choices' => array(
//                'Fixed' => 'One Off',
//                'Monthly' => 'Monthly',
//                'Weekly' => 'Weekly',
//                'Hourly' => 'Hourly'
//            ),
//            'required' => true,
//            'empty_data' => 'Not Available'
//        ));
//        $builder->add('status', 'choice', array(
//            'choices' => array(
//                'Available' => 'Available',
//                'Not Available' => 'Not Available'
//            ),
//            'required' => true,
//            'empty_data' => 'Not Available'
//        ));
//        $builder->add('Category', 'entity', array(
//            'class' => 'CRMSaleBundle:ProductCategory',
//            'property' => 'name',
//            'multiple' => true,
//            'expanded' => true,
//        ));
//       $builder->add('contacts_type', 'collection', array('type' => new ProductType()));
    }

    /**
     * Returns the default options/class for this form.
     * @param array $options
     * @return array The default options
     */
    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CRM\SaleBundle\Entity\Quote',
            'em' => '',
        );
    }

    /**
     * Mandatory in Symfony2
     * Gets the unique name of this form.
     * @return string
     */
    public function getName() {
        return 'addproduct';
    }

}
