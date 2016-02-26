<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SaleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface; 
class ShippingMethodType extends AbstractType {
    
    public function __construct($creationUser ) { 
        $this->creation_user = $creationUser;
    } 
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', 'text', array('required' => true));  
        $builder->add('description', 'text', array('required' => true)); 
        $builder->add('amount', 'text', array('required' => true));
        $builder->add('pricetype', 'entity', array(
            'class' => 'CRMSaleBundle:PayTerm',
            'empty_value' => '-- Select Terms --',
            'property' => 'name',
            'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                return $repository->createQueryBuilder('s')
                                ->where('s.creationUser = ?1')
                                ->setParameter(1, $this->creation_user)
                                ->add('orderBy', 's.id ASC');
            }
        ));
    }

    /**
     * Returns the default options/class for this form.
     * @param array $options
     * @return array The default options
     */
    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CRM\SaleBundle\Entity\ShippingMethod',
            'em' => '',
        );
    }

    /**
     * Mandatory in Symfony2
     * Gets the unique name of this form.
     * @return string
     */
    public function getName() {
        return 'shipping';
    }

}
