<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SaleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use CRM\SaleBundle\Entity\PayTerm;

class ProductType extends AbstractType {

    public function __construct($creationUser) {
        $this->creation_user = $creationUser;
    }

    /**
     * Builds the AddProduct form
     * @param  \Symfony\Component\Form\FormBuilder $builder
     * @param  array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('productname', 'text', array('required' => true));
        $builder->add('productcode', 'text', array('required' => false));
        $builder->add('description', 'textarea');
        $builder->add('price', 'text', array('required' => false));

        $builder->add('pricetype', 'text', array('required' => true));
//        $builder->add('pricetype', 'entity', array(
//            'class' => 'CRMSaleBundle:PayTerm',
//            'empty_value' => '-- Select Terms --',
//            'property' => 'name',
//            'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
//                return $repository->createQueryBuilder('s')
//                                ->where('s.creationUser = ?1')
//                                ->setParameter(1, $this->creation_user)
//                                ->add('orderBy', 's.id ASC');
//            }
//            ));

        $builder->add('status', 'choice', array(
            'choices' => array(
                'Available' => 'Available',
                'Not Available' => 'Not Available'
            ),
            'required' => true,
            'empty_data' => 'Not Available'
        ));
        $builder->add('Category', 'entity', array(
            'class' => 'CRMSaleBundle:ProductCategory',
            'property' => 'name',
            'multiple' => true,
            'expanded' => true,
            'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                return $repository->createQueryBuilder('s')
                                ->where('s.creationUser = ?1')
                                ->setParameter(1, $this->creation_user)
                                ->add('orderBy', 's.id ASC');
            }
        ));
        $builder->add('product_save', 'submit');

//       $builder->add('contacts_type', 'collection', array('type' => new ProductType()));
    }

    /**
     * Returns the default options/class for this form.
     * @param array $options
     * @return array The default options
     */
    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CRM\SaleBundle\Entity\Product',
            'em' => '',
        );
    }

    /**
     * Mandatory in Symfony2
     * Gets the unique name of this form.
     * @return string
     */
    public function getName() {
        return 'product';
    }

}
