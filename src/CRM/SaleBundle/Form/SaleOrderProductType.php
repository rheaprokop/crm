<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SaleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class SaleOrderProductType extends AbstractType {

    public function __construct($em, $product_id, $creationUser) {
        $this->em = $em;
        $this->product_id = $product_id;
        $this->creation_user = $creationUser;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $productlist = $this->em->getRepository('CRMSaleBundle:Product')->find($this->product_id);


        if ($this->product_id == "0") {
            $productname_data = "";
            $pricetype_data = "";
            $price = "";
            $prodcode = "";
        } else {
            $productname_data = $this->em->getReference("CRMSaleBundle:Product", $this->product_id);
            //$pricetype_data = $this->em->getReference("CRMSaleBundle:PayTerm", $productlist->getPricetype());
            $pricetype_data = $productlist->getPriceType();
            $price = $productlist->getPrice();
            $prodcode = $productlist->getProductCode();
        }

          $builder->add('productname', 'entity', array(
            'class' => 'CRMSaleBundle:Product',
            'empty_value' => '-- Select Product --',
            'property' => 'productname',
            'data' => $productname_data,
            'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                return $repository->createQueryBuilder('s')
                                ->where('s.creationUser = ?1')
                                ->setParameter(1, $this->creation_user)
                                ->add('orderBy', 's.productname ASC');
            }
        ));

        $builder->add('productprice', 'text', array('read_only' => true, 'required' => true, 'data' => $price));
        $builder->add('productcode', 'text', array('read_only' => true, 'required' => false, 'data' => $prodcode));
//        $builder->add('pricetype', 'entity', array(
//            'class' => 'CRMSaleBundle:PayTerm',
//            'property' => 'name',
//            'query_builder' => function(EntityRepository $er) {
//                    return $er->createQueryBuilder('e');
//                },
//            'data' => $pricetype_data
//        ));
       $builder->add('pricetype', 'text', array('read_only' => true, 'required' => true, 'data' => $pricetype_data));
        $builder->add('quantity', 'text', array('required' => true));
        $builder->add('discount', 'text', array('required' => true));
        $builder->add('surcharge', 'text', array('required' => true));
    }

    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CRM\ContactBundle\Entity\SaleOrderProduct',
            'em' => '',
        );
    }

    public function getName() {
        return 'orderproduct';
    }

}
