<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SaleBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface; 

class QuoteType extends AbstractType {
 

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('quoteid', 'hidden', array('required' => true));
        $builder->add('quotename', 'text', array('required' => true));
        $builder->add('expiration_date', 'date', array(
            'widget' => 'single_text', 
             'format' => 'yyyy-MM-dd',));
        $builder->add('account_manager', 'text', array('required' => false));
        $builder->add('account_name', 'text', array('required' => false));
//        $builder->add('account_name', 'entity', array(
//            'class' => 'CRMSaleBundle:Account',
//            'property' => 'name',
//            'empty_value' => 'Select An Options',
//            'query_builder' => function(EntityRepository $er) {
//        return $er->createQueryBuilder('u')
//                        ->where('u.creation_user = :owner')
//                        ->setParameter('owner', $this->securityContext->getToken()->getUser())
//                        ->orderBy('u.name', 'ASC');
//    },
//        ));
        $builder->add('contactPerson', 'text', array('required' => false)); 
        $builder->add('customerStreet', 'text', array('required' => false));
        $builder->add('customerCity', 'text', array('required' => false));
        $builder->add('customerState', 'text', array('required' => false));
        $builder->add('customerCountry', 'country', array(
            'label' => 'Please', 'preferred_choices' => array('US')
        ));
        $builder->add('customerFax', 'text', array('required' => false));
        $builder->add('customerMobile', 'text', array('required' => false));
        $builder->add('contact_phone', 'text', array('required' => false));
        $builder->add('contact_email', 'email', array('required' => false));
        $builder->add('additional_notes', 'textarea');
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
        return 'quote';
    }

}
