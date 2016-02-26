<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CRM\SaleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
// Import namespaces for Create contact properties 
use CRM\SaleBundle\Entity\SaleActivity;
use CRM\SaleBundle\Entity\Invoice;
use CRM\SaleBundle\Entity\InvoiceProduct;
use CRM\SaleBundle\Entity\Product;
use CRM\SaleBundle\Form\ProductType;
use CRM\SaleBundle\Form\SaleActivityType;
use CRM\SaleBundle\Form\InvoiceType;
use CRM\SaleBundle\Form\InvoiceProductType;
use CRM\SaleBundle\Form\InvoiceAmountType;
use CRM\SaleBundle\Form\InvoiceStatusType;
use CRM\UserBundle\Entity\UserActivity;
use CRM\UserBundle\Form\UserActivityType;

class InvoiceController extends Controller {

    public function createAction() {
        $this->getTheme();
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()
                ->getRepository('CRMUserBundle:GlobalParameter');

        $invoice_id_prepend = $repository->createQueryBuilder('p')
                ->where('p.parameterCode = :a') 
                ->andWhere('p.creationUser = :u')
                ->setParameter('a', 'INVOICE_NO') 
                ->setParameter('u', $this->getUser()->getUsername())
                ->getQuery()
                ->getOneOrNullResult();

        $contacts = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMContactBundle:Contact', 'b')
                ->addOrderBy('b.firstname', 'ASC')
                ->where('b.creation_user = :id')
                ->setParameter('id', $this->getUser()->getUsername())
                ->getQuery()
                ->getResult();

        $accounts = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMContactBundle:Account', 'b')
                ->addOrderBy('b.name', 'ASC')
                ->where('b.creation_user = :id')
                ->setParameter('id', $this->getUser()->getUsername())
                ->getQuery()
                ->getResult();

        $datetime = new \DateTime("now");
        $d = $datetime->format('ymdh');

        $invoice = new Invoice();
        $form = $this->get('form.factory')->create(new InvoiceType($em, ''), $invoice);
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $invoice->setCreationDate(new \DateTime());
                $invoice->setCreationUser($this->getUser()->getUsername());
                $invoice->setInvoiceNumber($d);
                $invoice->setQuoteId("quoteid");
                $invoice->setCustomerNumber("123");
                $invoice->setCustomerContactId("123");
                $invoice->setAmountCurrency($this->getCurrency());
                $invoice->setInvoiceStatus ( 'Draft' );
//                 $shippingmethod = $em->getRepository('CRMSaleBundle:ShippingMethod')->find($invoice->getShippingMethod());
//                 $invoice->setShippingMethod($shippingmethod->getId());
                $em->persist($invoice);
                $em->flush();

                $update_invoice = $em->getRepository('CRMSaleBundle:Invoice')->find($invoice->getId());
                $update_invoice->setInvoiceNumber($invoice_id_prepend->getParameterValue() . $d . $invoice->getId());
                $em->persist($update_invoice);
                $em->flush();

                $this->getUserActivity("creation_user created new invoice : " . $invoice->getInvoiceNumber());
                //$this->getSaleActivity("creation_user created new quotation: " . $invoice->getQuoteId(), "Order", $invoice->getId());
                //get first id in product
                //This page will redirect to View Profile.
                return $this->redirect($this->generateUrl('CRMSaleBundle_invoice_add_product', array(
                                    'id' => $invoice->getId(),
                                    'delete_product_id' => "0",
                                    'product_id' => "0",
                )));
            }
        }

        $my_products = $em->createQueryBuilder()
                        ->select('count(c.id)')
                        ->from('CRMSaleBundle:Product', 'c')
                        ->where('c.creationUser = :id')
                        ->setParameter('id', $this->getUser()->getUsername())
                        ->getQuery()->getSingleScalarResult();

        $product = new Product();
        $form_product = $this->get('form.factory')->create(new ProductType($this->getUser()->getUsername()), $product);

        if (!$my_products) {
            $this->get('session')->getFlashBag()->set('no_product_notice', 'alert');
        }
        return $this->render('CRMSaleBundle:Invoice:create.html.twig', array(
                    'contacts' => $contacts,
                    'accounts' => $accounts,
                    'form' => $form->createView(),
                    'form_product' => $form_product->createView()
        ));
    }

    public function invoiceAction($id, $status) {
        $this->getTheme();
        $em = $this->getDoctrine()->getManager();
        $invoice = $em->getRepository('CRMSaleBundle:Invoice')->find($id);
        
        if ($status != 0) {
        		
        	switch ($status) {
        		case '4f8ebbe84c8' :
        			$status = "Prepared";
        			break;
        		case '5706de961fb' :
        			$status = "Delivered";
        			break;
        		case '19abd416eb9' :
        			$status = "Unclear";
        			break;
        		case '14781ee5e85' :
        			$status = "Cleared";
        			break;
        		default :
        			$status = "Draft";
        			break;
        	}
        	$query = $em->createQuery ( 'UPDATE CRMSaleBundle:Invoice u SET u.invoiceStatus = \'' . $status . '\' WHERE u.id = \'' . $id . '\'' );
        		
        	$query->getResult ();
        	$edit_form = $this->get ( 'form.factory' )->create ( new InvoiceStatusType ( $status ), $invoice );
        } elseif ($status == "" || $status == 0) {
        	$edit_form = $this->get ( 'form.factory' )->create ( new InvoiceStatusType ( $invoice->getInvoiceStatus () ), $invoice );
        } else {
        	$edit_form = $this->get ( 'form.factory' )->create ( new InvoiceStatusType ( $invoice->getInvoiceStatus () ), $invoice );
        }

        //View list of product added during quote creation
        $productslist = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:InvoiceProduct', 'b')
                ->where('b.invoiceDBId = :invoiceId')
                ->setParameter('invoiceId', $id)
                ->addOrderBy('b.productId', 'ASC')
                ->getQuery()
                ->getResult();

        //show recent activities (5)
        //Populate recent contact for widget
        $offset = 0;
        $limit = 10;
        $recent_activities = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:SaleActivity', 'b')
                ->addOrderBy('b.dateAdded', 'DESC')
                ->where('b.quoteDBId = :id')
                ->setParameter('id', $id)
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();

        //display payterms table.
        $query = $em->createQuery(
                'SELECT SUM( b.quantity ) total_quantity, a.name, SUM( b.productprice ) total_price
                    FROM CRMSaleBundle:PayTerm a
                    INNER JOIN CRMSaleBundle:InvoiceProduct b WITH a.id = b.pricetype
                    WHERE b.id =  ' . $id . '
                        GROUP BY a.id'
        );

        $payterms_total = $query->getResult();




        $deleteForm = $this->createDeleteForm($id);
        return $this->render('CRMSaleBundle:Invoice:invoice.html.twig', array(
                    'productslist' => $productslist,
                    'invoice' => $invoice,
        			'form' => $edit_form->createView (),
                    'payterms' => $payterms_total,
                    'recent_activities' => $recent_activities,
                    'total_discount' => $this->getProdAmount($id, $em, 'discount'),
                    'total_surcharge' => $this->getProdAmount($id, $em, 'surcharge'),
                    'total_shipping' => $this->getInvoiceFieldValue($id, $em, 'totalShipping'),
                    'total_vat' => $this->getInvoiceFieldValue($id, $em, 'totalVat'),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    public function manageAction() {
        $this->getTheme();
        $em = $this->getDoctrine()->getManager();


        $count = $em->createQueryBuilder()
                        ->select('count(a.id)')
                        ->from('CRMSaleBundle:Invoice', 'a')
                        ->where('a.creationUser = :user')
                        ->setParameter('user', $this->getUser()->getUsername())
                        ->getQuery()->getSingleScalarResult();


        $invoices = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:Invoice', 'b')
                ->where('b.creationUser = :user')
                ->setParameter('user', $this->getUser()->getUsername())
                // ->addOrderBy('b.quoteName', 'ASC')
                ->getQuery()
                ->getResult();

        //Populate recent contact for widget
        $offset = 0;
        $limit = 1;
        $invoice_recent = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:Invoice', 'b')
                ->where('b.creationUser = :user')
                ->setParameter('user', $this->getUser()->getUsername())
                ->addOrderBy('b.creationDate', 'DESC')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();


        return $this->render('CRMSaleBundle:Invoice:manage.html.twig', array(
                    'invoices' => $invoices,
                    'invoice_recent' => $invoice_recent,
                    //'categories' => $categories,
                    'count' => $count,
        		'prepared' => $this->countStatus("Prepared"),
        		'delivered' => $this->countStatus("Delivered"),
        		'unclear' => $this->countStatus("Unclear"),
        		'cleared' => $this->countStatus("Cleared"),
        ));
    }

    /**
     * This forms display the data and will call updateAction to continually update the provided $id
     *
     * @Route("/{id}/edit", name="CRMSaleBundle_invoice_edit")
     * @Template()
     */
    public function editAction($id) {
        $this->getTheme();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CRMSaleBundle:Invoice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invoice entity.');
        }

        $edit_form = $this->createForm(new InvoiceType($em, $id), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $edit_form->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Invoice entity.
     *
     * @Route("/{id}/update", name="CRMSaleBundle_invoice_edit")
     * @Method("post")
     * @Template("CRMSaleBundle:Invoice:edit.html.twig")
     */
    public function updateAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CRMSaleBundle:Invoice')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invoice entity.');
        }

        $edit_form = $this->createForm(new InvoiceType($em, $id), $entity);
        
        $request = $this->getRequest();
        $edit_form->bind($request);


        if ($edit_form->isValid()) {
        	$entity->setAmountCurrency($this->getCurrency());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('CRMSaleBundle_invoice_add_product', array(
                                'id' => $entity->getId(),
                                'delete_product_id' => "0",
                                'product_id' => "0",
            )));
        }

        return array(
            'entity' => $entity,
            'product_id' => '0',
            'delete_product_id' => '0',
            'edit_form' => $edit_form->createView(),
        );
    }

    public function addproductAction($id, $delete_product_id, $product_id) {
        $this->getTheme();
        $em = $this->getDoctrine()->getManager();

        $invoice = $em->getRepository('CRMSaleBundle:Invoice')->find($id);

        if ($product_id == "0") {
            $query = $em->createQuery('SELECT u.id FROM CRMSaleBundle:Product u');
            $users = $query->getResult(); // array of CmsUser username and name values
            $productid = $users[0]['id'];
            $productlist = $em->getRepository('CRMSaleBundle:Product')->find($productid);
            $product_id = '0';
        } else {
            $productlist = $em->getRepository('CRMSaleBundle:Product')->find($product_id);
        }
        $current_del_id = $delete_product_id;
        $deleteproductForm = $this->createDeleteForm($delete_product_id);

        $invoice_product = new InvoiceProduct();
        $form = $this->get('form.factory')->create(new InvoiceProductType($em, $product_id, $this->getUser()->getUsername()), $invoice_product);
        $edit_form = $this->createForm(new InvoiceAmountType(), $invoice);

        $request = $this->get('request');

        //Show list of product added during quote creation.
        $products = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:InvoiceProduct', 'b')
                ->where('b.invoiceDBId = :invoiceDBId')
                ->setParameter('invoiceDBId', $id)
                ->addOrderBy('b.productId', 'ASC')
                ->getQuery()
                ->getResult();


        $payterms = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:PayTerm', 'b')
                ->getQuery()
                ->getResult();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {

                $invoice_product->setinvoiceDBId($id);
                $invoice_product->setQuoteId("234");
                $product = $em->getRepository('CRMSaleBundle:Product')->find($invoice_product->getProductName());
                $invoice_product->setProductId($product->getId());
                $invoice_product->setProductCode($product->getProductCode());
                $invoice_product->setProductPrice($product->getPrice());
                $invoice_product->setPricetype($product->getPricetype());
                $total_product = $invoice_product->getProductPrice() * $invoice_product->getQuantity();
                $discount = (($invoice_product->getDiscount() / 100) * $total_product);
                $surcharge = (($invoice_product->getSurcharge() / 100) * $total_product);
                $invoice_product->setAmount(($total_product - $discount) + $surcharge); //add surcharge
                
                $em = $this->get('doctrine')->getManager();
                $em->persist($invoice_product);
                $em->flush();

                $this->getUserActivity("creation_user update invoice : " . $invoice_product->getinvoiceDBId());

                //$this->getSaleActivity("creation_user added product: " . $invoice_product->getProductName() . " for " . $invoice_id, "Quote", $id);
                return $this->redirect($this->generateUrl('CRMSaleBundle_invoice_add_product', array(
                                    'id' => $id,
                                    'product_id' => $productlist->getId(),
                                    'delete_product_id' => '0',
                )));
            }
        }

        //display payterms table.
        $query = $em->createQuery(
                'SELECT SUM( a.quantity ) total_quantity, a.pricetype, SUM( a.productprice ) total_price
                    FROM CRMSaleBundle:InvoiceProduct a WHERE a.invoiceDBId = \'' . $id . '\''
        );

        $payterms_total = $query->getResult();


        return $this->render('CRMSaleBundle:Invoice:addproduct.html.twig', array(
                    'products' => $products,
        			'currency' => $this->getCurrency(),
                    'invoice' => $invoice,
                    'product_id' => $productlist,
                    'payterms' => $payterms,
                    'total_discount' => $this->getProdAmount($id, $em, 'discount'),
                    'total_surcharge' => $this->getProdAmount($id, $em, 'surcharge'),
                    'delete_product_form' => $deleteproductForm->createView(),
                    'payterms_total' => $payterms_total,
        			'subtotal_total' => $this->getProdAmount($id, $em, 'amount'),
                    'edit_form' => $edit_form->createView(),
                    'form' => $form->createView(),
                    'current_del_id' => $current_del_id,
        ));
    }

    /**
     * Deletes from Invoice entity.
     *
     * @Route("/{id}/delete", name="CRMSaleBundle_invoice_delete")
     * @Method("post")
     */
    public function deleteAction($id) {
        $delete_form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $delete_form->bind($request);

        if ($delete_form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CRMSaleBundle:Invoice')->find($id);


            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Delete entity.');
            }

            $em->remove($entity);
            $em->flush();

            $query = $em->createQuery('DELETE CRMSaleBundle:InvoiceProduct c WHERE c.invoiceDBId = ' . $id . '');
            $query->execute();

            $this->getSaleActivity("creation_user deleted invoice: " . $entity->getQuoteId(), "Invoice", $id);
        }

        return $this->redirect($this->generateUrl('CRMSaleBundle_sale_invoice_manage'));
    }

    public function delete_productAction($id, $delete_product_id) {
        $delete_form = $this->createDeleteForm($delete_product_id);
        $request = $this->getRequest();

        $delete_form->bind($request);

        if ($delete_form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CRMSaleBundle:InvoiceProduct')->find($delete_product_id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Invoice entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->getSaleActivity("creation_user deleted product: " . $entity->getProductName() . " in " . $id, "Invoice", $id);
        }

        return $this->redirect($this->generateUrl('CRMSaleBundle_invoice_add_product', array(
                            'id' => $id,
                            'product_id' => '0',
                            'delete_product_id' => $delete_product_id,
        )));
    }

    public function updateamountAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CRMSaleBundle:Invoice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invoice entity.');
        }

        $edit_form = $this->createForm(new InvoiceAmountType($em, '0'), $entity);
        $request = $this->getRequest();
        $edit_form->bind($request);

        if ($edit_form->isValid()) {
        	$entity->setInvoiceStatus ( 'Prepared' );
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('CRMSaleBundle_invoice_view', array('id' => $id,'status' => '4f8ebbe84c8')));
        }
    }

    public function viewAction($id) {
        $this->getTheme();
        $em = $this->getDoctrine()->getManager();

        $invoice = $em->getRepository('CRMSaleBundle:Invoice')->find($id);

        //View list of product added during quote creation
        $productslist = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:InvoiceProduct', 'b')
                ->where('b.invoiceDBId = :invoiceId')
                ->setParameter('invoiceId', $id)
                ->addOrderBy('b.productId', 'ASC')
                ->getQuery()
                ->getResult();

        //show recent activities (5)
        //Populate recent contact for widget
        $offset = 0;
        $limit = 10;
        $recent_activities = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:SaleActivity', 'b')
                ->addOrderBy('b.dateAdded', 'DESC')
                ->where('b.quoteDBId = :id')
                ->setParameter('id', $id)
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();

        //display payterms table.
        $query = $em->createQuery(
                'SELECT SUM( a.quantity ) total_quantity, a.pricetype, SUM( a.productprice ) total_price
                    FROM CRMSaleBundle:InvoiceProduct a WHERE a.invoiceDBId = \'' . $id . '\''
        );

        $payterms_total = $query->getResult();

        $deleteForm = $this->createDeleteForm($id);
        $user = $em->getRepository('CRMUserBundle:User')->find($this->getUser()->getId());

        return $this->render('CRMSaleBundle:Invoice:view.html.twig', array(
                    'productslist' => $productslist,
                    'invoice' => $invoice,
                    'user' => $user,
        			'currency' => $this->getCurrency(),
                    'payterms' => $payterms_total,
                    'recent_activities' => $recent_activities,
                    'total_discount' => $this->getProdAmount($id, $em, 'discount'),
                    'total_surcharge' => $this->getProdAmount($id, $em, 'surcharge'),
                    'total_shipping' => $this->getInvoiceFieldValue($id, $em, 'amountDue'),
                    'total_vat' => $this->getInvoiceFieldValue($id, $em, 'totalVat'),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm();
    }

    public function getProdAmount($id, $em, $field) {
        //Get total discount of products inserted based on provided id.
        $total = $em->createQueryBuilder()
                ->select('sum(mf.' . $field . ')')
                ->from('CRMSaleBundle:InvoiceProduct', 'mf')
                ->where('mf.invoiceDBId = :Id')
                ->setParameter('Id', $id)
                ->getQuery()
                ->getSingleScalarResult();
        return $total;
    }

    public function getInvoiceFieldValue($id, $em, $field) {
        $total = $em->createQueryBuilder()
                ->select('sum(mf.' . $field . ')')
                ->from('CRMSaleBundle:Invoice', 'mf')
                ->where('mf.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getSingleScalarResult();
        return $total;
    }

    public function getUnitName($em, $unit_id, $field) {
        $unit = $em->createQueryBuilder()
                ->select('sum(mf.' . $field . ')')
                ->from('CRMSaleBundle:PayTerm', 'mf')
                ->where('mf.id = :id')
                ->setParameter('id', $unit_id)
                ->getQuery()
                ->getSingleScalarResult();
        return $unit;
    }

    public function getSaleActivity($activiydesc, $salemodule, $quotedb_id) {

        $activity = new SaleActivity();
        $form1 = $this->get('form.factory')->create(new SaleActivityType(), $activity);
        $request1 = $this->get('request');

        if ($request1->getMethod() == 'POST') {
            $form1->bind($request1);
            $activity->setActivityDesc($activiydesc);
            $activity->setDateAdded(new \DateTime());
            $activity->setActivityUser($this->getUser()->getUsername());
            $activity->setSaleModule($salemodule);
            $activity->setQuoteDBId($quotedb_id);
            $em1 = $this->get('doctrine')->getManager();
            $em1->persist($activity);
            $em1->flush();
        }
    }

    public function createprodAction() {
        $this->getTheme();
        $product = new Product();
        $form_product = $this->get('form.factory')->create(new ProductType($this->getUser()->getUsername()), $product);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form_product->bind($request);
            if ($form_product->isValid()) {

                $em = $this->get('doctrine')->getManager();
                $product->setCreationUser($this->getUser()->getUsername());
                $product->setDateAdded(new \DateTime());
//                $paytype = $em->getRepository('CRMSaleBundle:PayTerm')->find($product->getPricetype());
//                $product->setPricetype($paytype->getId());

                $em->persist($product);
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('CRMSaleBundle_sale_invoice_manage'));
    }

    public function getUserActivity($activiydesc) {
        $user_activity = new UserActivity();

        $form1 = $this->get('form.factory')->create(new UserActivityType(), $user_activity);

        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form1->bind($request);

            $user_activity->setActivityDesc($activiydesc);
            $user_activity->setDateAdded(new \DateTime());
            $user_activity->setModule('Invoice');
            $user_activity->setActivityUser($this->getUser()->getUsername());
            $em = $this->get('doctrine')->getManager();
            $em->persist($user_activity);
            $em->flush();
        }
    }

    public function getCurrency() {
    	$repository = $this->getDoctrine()
    	->getRepository('CRMUserBundle:GlobalParameter');
    
    	$currency = $repository->createQueryBuilder('p')
    	->where('p.parameterCode = :a')
    	->andWhere('p.creationUser = :u')
    	->setParameter('a', 'CURRENCY')
    	->setParameter('u', $this->getUser()->getUsername())
    	->getQuery()
    	->getOneOrNullResult();
    
    	if (!$currency) {
    		$curr = "USD";
    	} else {
    		$curr = $currency->getParameterValue();
    	}
    
    	return $curr;
    }
    
    public function getTheme() {
        $request = $this->getRequest();
        if ($request->query->get('skin')) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('CRMUserBundle:User')->find($this->getUser()->getId());
            $user->setTheme($request->query->get('skin'));
            $em->flush();
        }
    }

    public function countStatus($field) {
    	$em = $this->getDoctrine ()->getManager ();
    	$c_qry = $em->createQueryBuilder ()
    	->select ( 'count(c.id)' )
    	->from ( 'CRMSaleBundle:Invoice', 'c' )
    	->where ( 'c.creationUser = :id' )
    	->andWhere ( 'c.invoiceStatus = :s' )
    	->setParameter ( 'id', $this->getUser ()->getUserName () )
    	->setParameter ( 's', ''.$field.'' )
    	->getQuery ()->getSingleScalarResult ();
    		
    	return $c_qry;
    }
}
