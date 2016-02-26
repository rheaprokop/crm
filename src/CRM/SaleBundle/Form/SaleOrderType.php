<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SaleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class SaleOrderType extends AbstractType {

    public function __construct($em, $id, $creationUser ) {
        $this->em = $em;
        $this->id = $id;
        $this->creation_user = $creationUser;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $order = $this->em->getRepository('CRMSaleBundle:SaleOrder')->find($this->id);


//        if ($this->id == "0" || $this->id == "") {
//            $shipping_data = "";
//        } else {
//            $shipping_data = $this->em->getReference("CRMSaleBundle:ShippingMethod", $order->getShippingMethod());
//        }

        $builder->add('orderDate', 'date', array(
            'widget' => 'single_text', ));
       // $builder->add('orderReference', 'hidden', array('required' => false));
        $builder->add('poOrder', 'text', array('required' => false));
        $builder->add('quoteId', 'text', array('required' => false));
        
        $builder->add('customerName', 'text', array('required' => false));
        $builder->add('contactPerson', 'text', array('required' => false));
        $builder->add('customerStreet', 'text', array('required' => false));
        $builder->add('customerCity', 'text', array('required' => false));
        $builder->add('customerState', 'text', array('required' => false));
        $builder->add('customerCountry', 'country', array(
            'label' => 'Please', 'preferred_choices' => array('US')
        ));
        $builder->add('customerEmail', 'email', array('required' => false));
        $builder->add('customerFax', 'text', array('required' => false));
        $builder->add('customerMobile', 'text', array('required' => false));
        $builder->add('customerPhone', 'text', array('required' => false));
        $builder->add('billingName', 'text', array('required' => false));
        $builder->add('billingStreet', 'text', array('required' => false));
        $builder->add('billingCity', 'text', array('required' => false));
        $builder->add('billingState', 'text', array('required' => false));
        $builder->add('billingCountry', 'country', array(
            'label' => 'Please', 'preferred_choices' => array('US')
        ));
        $builder->add('billingPhone', 'text', array('required' => false));
        $builder->add('billingMobile', 'text', array('required' => false));
        $builder->add('billingFax', 'text', array('required' => false));
        $builder->add('billingEmail', 'email', array('required' => false));
        $builder->add('shippingName', 'text', array('required' => false));
        $builder->add('shippingStreet', 'text', array('required' => false));
        $builder->add('shippingCity', 'text', array('required' => false));
        $builder->add('shippingState', 'text', array('required' => false));
        $builder->add('shippingCountry', 'country', array(
            'label' => 'Please', 'preferred_choices' => array('US')
        ));
        $builder->add('shippingPhone', 'text', array('required' => false));
        $builder->add('shippingMobile', 'text', array('required' => false));
        $builder->add('shippingFax', 'text', array('required' => false));
        $builder->add('shippingEmail', 'email', array('required' => false));
       // $builder->add('shippingTotal', 'text', array('required' => false));
        $builder->add('shippingMethod', 'entity', array(
            'class' => 'CRMSaleBundle:ShippingMethod',
            'property' => 'name',
           'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                return $repository->createQueryBuilder('s')
                                ->where('s.creationUser = ?1')
                                ->setParameter(1, $this->creation_user)
                                ->add('orderBy', 's.id ASC');
            },
            //'data' => $shipping_data
        ));
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
            'data_class' => 'CRM\SaleBundle\Entity\SaleOrder',
            'em' => '',
        );
    }

    /**
     * Mandatory in Symfony2
     * Gets the unique name of this form.
     * @return string
     */
    public function getName() {
        return 'order';
    }

}
