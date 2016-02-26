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
use CRM\SaleBundle\Entity\SaleOrder;
use CRM\SaleBundle\Entity\SaleOrderProduct;
use CRM\SaleBundle\Entity\Product;
use CRM\SaleBundle\Entity\Invoice;
use CRM\SaleBundle\Form\SaleActivityType;
use CRM\SaleBundle\Form\ProductType;
use CRM\SaleBundle\Form\SaleOrderProductType;
use CRM\SaleBundle\Form\SaleOrderStatusType;
use CRM\SaleBundle\Form\SaleOrderType;
use CRM\SaleBundle\Form\OrderAmountType;
use CRM\UserBundle\Entity\UserActivity;
use CRM\UserBundle\Form\UserActivityType;


class OrderController extends Controller {
	public function createAction() {
		$this->getTheme ();
		$em = $this->get ( 'doctrine' )->getManager ();
		$repository = $this->getDoctrine ()->getRepository ( 'CRMUserBundle:GlobalParameter' );
		$order_id_prepend = $repository->createQueryBuilder ( 'p' )->where ( 'p.parameterCode = :a' )->andWhere ( 'p.creationUser = :u' )->setParameter ( 'a', 'ORDER_REF' )->setParameter ( 'u', $this->getUser ()->getUsername () )->getQuery ()->getOneOrNullResult ();
		
		if (! $order_id_prepend) {
			$order_prepend = "0";
		} else {
			$order_prepend = $order_id_prepend->getParameterValue ();
		}
		
		$repository_cust = $this->getDoctrine ()->getRepository ( 'CRMUserBundle:GlobalParameter' );
		$cust_id_prepend = $repository_cust->createQueryBuilder ( 'p' )->where ( 'p.parameterCode = :a' )->andWhere ( 'p.creationUser = :u' )->setParameter ( 'a', 'CUST_NO' )->setParameter ( 'u', $this->getUser ()->getUsername () )->getQuery ()->getOneOrNullResult ();
		
		if (! $cust_id_prepend) {
			$cust_prepend = "0";
		} else {
			$cust_prepend = $cust_id_prepend->getParameterValue ();
		}
		
		$contacts = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMContactBundle:Contact', 'b' )->addOrderBy ( 'b.firstname', 'ASC' )->where ( 'b.creation_user = :id' )->setParameter ( 'id', $this->getUser ()->getUsername () )->getQuery ()->getResult ();
		
		$order = new SaleOrder ();
		$form = $this->get ( 'form.factory' )->create ( new SaleOrderType ( $em, '', $this->getUser ()->getUsername () ), $order );
		$request = $this->get ( 'request' );
		
		if ($request->getMethod () == 'POST') {
			$form->bind ( $request );
			if ($form->isValid ()) {
				
				$order->setCreationDate ( new \DateTime () );
				$order->setCreationUser ( $this->getUser ()->getUsername () );
				
				$datetime = new \DateTime ( "now" );
				$d = $datetime->format ( 'ymdh' );
				$order->setQuoteId ( "quoteid" );
				// set date + contact_id + order_contact_id
				
				$order->setCustomerNumber ( $cust_prepend . $d );
				$order->setCustomerContactId ( $cust_prepend . $d );
				$order->setOrderReference ( $order_prepend . $d );
				// $shippingmethod = $em->getRepository('CRMSaleBundle:ShippingMethod')->find($order->getShippingMethod());
				// $order->setShippingMethod($shippingmethod->getId());
				$order->setAmountCurrency ( $this->getCurrency () );
				$em->persist ( $order );
				$em->flush ();
				
				$update_order = $em->getRepository ( 'CRMSaleBundle:SaleOrder' )->find ( $order->getId () );
				$update_order->setOrderReference ( $order->getOrderReference () . $order->getId () );
				$update_order->setCustomerNumber ( $order->getCustomerContactId () . $order->getId () );
				$em->persist ( $update_order );
				$em->flush ();
				
				$this->getUserActivity ( "creation_user created new order: " . $update_order->getOrderReference () );
				
				// $this->getSaleActivity("creation_user created new quotation: " . $order->getQuoteId(), "Order", $order->getId());
				// get first id in product
				// This page will redirect to View Profile.
				return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_order_add_product', array (
						'id' => $order->getId (),
						'delete_product_id' => "0",
						'product_id' => "0" 
				) ) );
			}
		}
		$my_products = $em->createQueryBuilder ()->select ( 'count(c.id)' )->from ( 'CRMSaleBundle:Product', 'c' )->where ( 'c.creationUser = :id' )->setParameter ( 'id', $this->getUser ()->getUsername () )->getQuery ()->getSingleScalarResult ();
		
		$product = new Product ();
		$form_product = $this->get ( 'form.factory' )->create ( new ProductType ( $this->getUser ()->getUsername () ), $product );
		
		if (! $my_products) {
			$this->get ( 'session' )->getFlashBag ()->set ( 'no_product_notice', 'alert' );
		}
		return $this->render ( 'CRMSaleBundle:Order:create.html.twig', array (
				'prepend' => $order_id_prepend,
				'contacts' => $contacts,
				'form' => $form->createView (),
				'form_product' => $form_product->createView () 
		) );
	}
	public function orderAction($id, $status) {
		$this->getTheme ();
		$em = $this->getDoctrine ()->getManager ();
		$order = $em->getRepository ( 'CRMSaleBundle:SaleOrder' )->find ( $id );
		
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
			$query = $em->createQuery ( 'UPDATE CRMSaleBundle:SaleOrder u SET u.orderStatus = \'' . $status . '\' WHERE u.id = \'' . $id . '\'' );
			
			$query->getResult ();
			$edit_form = $this->get ( 'form.factory' )->create ( new SaleOrderStatusType ( $status ), $order );
		} elseif ($status == "" || $status == 0) {
			$edit_form = $this->get ( 'form.factory' )->create ( new SaleOrderStatusType ( $order->getOrderStatus () ), $order );
		} else {
			$edit_form = $this->get ( 'form.factory' )->create ( new SaleOrderStatusType ( $order->getOrderStatus () ), $order );
		}
		
		// View list of product added during quote creation
		$productslist = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:SaleOrderProduct', 'b' )->where ( 'b.orderDBId = :orderId' )->setParameter ( 'orderId', $id )->addOrderBy ( 'b.productId', 'ASC' )->getQuery ()->getResult ();
		
		// show recent activities (5)
		// Populate recent contact for widget
		$offset = 0;
		$limit = 10;
		$recent_activities = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:SaleActivity', 'b' )->addOrderBy ( 'b.dateAdded', 'DESC' )->where ( 'b.quoteDBId = :id' )->setParameter ( 'id', $id )->setFirstResult ( $offset )->setMaxResults ( $limit )->getQuery ()->getResult ();
		
		// display payterms table.
		$query = $em->createQuery ( 'SELECT SUM( b.quantity ) total_quantity, a.name, SUM( b.productprice ) total_price
                    FROM CRMSaleBundle:PayTerm a
                    INNER JOIN CRMSaleBundle:SaleOrderProduct b WITH a.id = b.pricetype
                    WHERE b.id =  ' . $id . '
                        GROUP BY a.id' );
		
		$payterms_total = $query->getResult ();
		
		$deleteForm = $this->createDeleteForm ( $id );
		return $this->render ( 'CRMSaleBundle:Order:order.html.twig', array (
				'productslist' => $productslist,
				'currency' => $this->getCurrency (),
				'order' => $order,
				'form' => $edit_form->createView (),
				'payterms' => $payterms_total,
				'recent_activities' => $recent_activities,
				'total_discount' => $this->getProdAmount ( $id, $em, 'discount' ),
				'total_surcharge' => $this->getProdAmount ( $id, $em, 'surcharge' ),
				'total_shipping' => $this->getQuoteFieldValue ( $id, $em, 'amountDue' ),
				'total_vat' => $this->getQuoteFieldValue ( $id, $em, 'totalVat' ),
				'delete_form' => $deleteForm->createView () 
		) );
	}
	public function manageAction() {
		$this->getTheme ();
		$em = $this->getDoctrine ()->getManager ();
		
		$count = $em->createQueryBuilder ()->select ( 'count(quote.id)' )->from ( 'CRMSaleBundle:SaleOrder', 'quote' )->where ( 'quote.creationUser = :user' )->setParameter ( 'user', $this->getUser ()->getUsername () )->getQuery ()->getSingleScalarResult ();
		
		$orders = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:SaleOrder', 'b' )->where ( 'b.creationUser = :user' )->setParameter ( 'user', $this->getUser ()->getUsername () )->
		// ->addOrderBy('b.quoteName', 'ASC')
		getQuery ()->getResult ();
		
		// Populate recent contact for widget
		$offset = 0;
		$limit = 1;
		$order_recent = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:SaleOrder', 'b' )->where ( 'b.creationUser = :user' )->setParameter ( 'user', $this->getUser ()->getUsername () )->addOrderBy ( 'b.creationDate', 'DESC' )->setFirstResult ( $offset )->setMaxResults ( $limit )->getQuery ()->getResult ();
		
		return $this->render ( 'CRMSaleBundle:Order:manage.html.twig', array (
				'orders' => $orders,
				'order_recent' => $order_recent, 
				'count' => $count,
				'prepared' => $this->countStatus("Prepared"),
				'delivered' => $this->countStatus("Delivered"),
				'unclear' => $this->countStatus("Unclear"),
				'cleared' => $this->countStatus("Cleared"),
		) );
	}
	public function addproductAction($id, $delete_product_id, $product_id) {
		$this->getTheme ();
		$em = $this->getDoctrine ()->getManager ();
		
		$order = $em->getRepository ( 'CRMSaleBundle:SaleOrder' )->find ( $id );
		
		if ($product_id == "0") {
			$query = $em->createQuery ( 'SELECT u.id FROM CRMSaleBundle:Product u' );
			$users = $query->getResult (); // array of CmsUser username and name values
			$productid = $users [0] ['id'];
			$productlist = $em->getRepository ( 'CRMSaleBundle:Product' )->find ( $productid );
			$product_id = '0';
		} else {
			$productlist = $em->getRepository ( 'CRMSaleBundle:Product' )->find ( $product_id );
		}
		$current_del_id = $delete_product_id;
		$deleteproductForm = $this->createDeleteForm ( $delete_product_id );
		
		$order_product = new SaleOrderProduct ();
		$form = $this->get ( 'form.factory' )->create ( new SaleOrderProductType ( $em, $product_id, $this->getUser ()->getUsername () ), $order_product );
		$edit_form = $this->createForm ( new OrderAmountType (), $order );
		
		$request = $this->get ( 'request' );
		
		// Show list of product added during quote creation.
		$products = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:SaleOrderProduct', 'b' )->where ( 'b.orderDBId = :orderDBId' )->setParameter ( 'orderDBId', $id )->addOrderBy ( 'b.productId', 'ASC' )->getQuery ()->getResult ();
		
		$payterms = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:PayTerm', 'b' )->getQuery ()->getResult ();
		
		if ($request->getMethod () == 'POST') {
			$form->bind ( $request );
			if ($form->isValid ()) {
				
				$order_product->setOrderDBId ( $id );
				$order_product->setQuoteId ( "000" );
				$product = $em->getRepository ( 'CRMSaleBundle:Product' )->find ( $order_product->getProductName () );
				$order_product->setProductId ( $product->getId () );
				$order_product->setProductCode ( $product->getProductCode () );
				$order_product->setProductPrice ( $product->getPrice () );
				$order_product->setPricetype ( $product->getPricetype () );
				
				$total_product = $order_product->getProductPrice () * $order_product->getQuantity ();
				$discount = (($order_product->getDiscount () / 100) * $total_product);
				$surcharge = (($order_product->getSurcharge () / 100) * $total_product);
				$order_product->setAmount ( ($total_product - $discount) + $surcharge ); // add surcharge
				
				$order_product->setCreationUser ( $this->getUser ()->getUsername () );
				$order_product->setCreationDate ( new \DateTime () );
				$em = $this->get ( 'doctrine' )->getManager ();
				$em->persist ( $order_product );
				$em->flush ();
				
				$this->getUserActivity ( "creation_user added new product to Sale Order No: " . $order->getOrderReference () );
				
				// $this->getSaleActivity("creation_user added product: " . $order_product->getProductName() . " for " . $order_id, "Quote", $id);
				return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_order_add_product', array (
						'id' => $id,
						'product_id' => $productlist->getId (),
						'delete_product_id' => '0' 
				) ) );
			}
		}
		
		// display payterms table.
		// $query = $em->createQuery(
		// 'SELECT SUM( b.quantity ) total_quantity, a.name, SUM( b.productprice ) total_price
		// FROM CRMSaleBundle:PayTerm a
		// INNER JOIN CRMSaleBundle:SaleOrderProduct b WITH a.id = b.pricetype
		// WHERE b.orderDBId = ' . $id . '
		// GROUP BY a.id'
		// );
		$query = $em->createQuery ( 'SELECT SUM( a.quantity ) total_quantity, a.pricetype, SUM( a.productprice ) total_price
                    FROM CRMSaleBundle:SaleOrderProduct a WHERE a.orderDBId = \'' . $id . '\'' );
		$payterms_total = $query->getResult ();
		
		return $this->render ( 'CRMSaleBundle:Order:addproduct.html.twig', array (
				'products' => $products,
				'quote' => $order,
				'currency' => $this->getCurrency (),
				'product_id' => $productlist,
				'payterms' => $payterms,
				'total_discount' => $this->getProdAmount ( $id, $em, 'discount' ),
				'total_surcharge' => $this->getProdAmount ( $id, $em, 'surcharge' ),
				'delete_product_form' => $deleteproductForm->createView (),
				'payterms_total' => $payterms_total,
				'subtotal_total' => $this->getProdAmount ( $id, $em, 'amount' ),
				'edit_form' => $edit_form->createView (),
				'form' => $form->createView (),
				'current_del_id' => $current_del_id 
		) );
	}
	public function updateamountAction($id) {
		$this->getTheme ();
		$em = $this->getDoctrine ()->getManager ();
		$entity = $em->getRepository ( 'CRMSaleBundle:SaleOrder' )->find ( $id );
		
		if (! $entity) {
			throw $this->createNotFoundException ( 'Unable to find Order entity.' );
		}
		
		$edit_form = $this->createForm ( new OrderAmountType ( $em, '0' ), $entity );
		$request = $this->getRequest ();
		$edit_form->bind ( $request );
		
		if ($edit_form->isValid ()) {
			$em->persist ( $entity );
			$em->flush ();
			
			return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_order_view', array (
					'id' => $id 
			) ) );
		}
	}
	
	/**
	 * Deletes from Order entity.
	 *
	 * @Route("/{id}/delete", name="CRMSaleBundle_order_delete")
	 * @Method("post")
	 */
	public function deleteAction($id) {
		$delete_form = $this->createDeleteForm ( $id );
		$request = $this->getRequest ();
		
		$delete_form->bind ( $request );
		
		if ($delete_form->isValid ()) {
			$em = $this->getDoctrine ()->getEntityManager ();
			$entity = $em->getRepository ( 'CRMSaleBundle:SaleOrder' )->find ( $id );
			
			if (! $entity) {
				throw $this->createNotFoundException ( 'Unable to find Delete entity.' );
			}
			
			$em->remove ( $entity );
			$em->flush ();
			
			$query = $em->createQuery ( 'DELETE CRMSaleBundle:SaleOrderProduct c WHERE c.orderDBId = ' . $id . '' );
			$query->execute ();
			$this->getUserActivity ( "creation_user deleted order: " . $entity->getOrderReference () );
			
			$this->getSaleActivity ( "creation_user deleted order: " . $entity->getOrderReference (), "Order", $entity->getOrderReference () );
		}
		
		return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_sale_order_manage' ) );
	}
	
	/**
	 * This forms display the data and will call updateAction to continually update the provided $id
	 *
	 * @Route("/{id}/edit", name="CRMSaleBundle_order_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$this->getTheme ();
		$em = $this->getDoctrine ()->getManager ();
		
		$entity = $em->getRepository ( 'CRMSaleBundle:SaleOrder' )->find ( $id );
		
		if (! $entity) {
			throw $this->createNotFoundException ( 'Unable to find Order entity.' );
		}
		
		$edit_form = $this->createForm ( new SaleOrderType ( $em, $id, $this->getUser ()->getUsername () ), $entity );
		$deleteForm = $this->createDeleteForm ( $id );
		
		return array (
				'entity' => $entity,
				'edit_form' => $edit_form->createView (),
				'delete_form' => $deleteForm->createView () 
		);
	}
	
	/**
	 * Edits an existing SaleOrder entity.
	 *
	 * @Route("/{id}/update", name="CRMSaleBundle_order_edit")
	 * @Method("post")
	 * @Template("CRMSaleBundle:Order:edit.html.twig")
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine ()->getManager ();
		$entity = $em->getRepository ( 'CRMSaleBundle:SaleOrder' )->find ( $id );
		if (! $entity) {
			throw $this->createNotFoundException ( 'Unable to find Sale Order entity.' );
		}
		
		$edit_form = $this->createForm ( new SaleOrderType ( $em, $id, $this->getUser ()->getUsername () ), $entity );
		$request = $this->getRequest ();
		$edit_form->bind ( $request );
		
		if ($edit_form->isValid ()) {
			$entity->setAmountCurrency ( $this->getCurrency () );
			$em->persist ( $entity );
			$em->flush ();
			
			return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_order_add_product', array (
					'id' => $entity->getId (),
					'delete_product_id' => "0",
					'product_id' => "0" 
			) ) );
		}
		
		return array (
				'entity' => $entity,
				'product_id' => '0',
				'delete_product_id' => '0',
				'edit_form' => $edit_form->createView () 
		);
	}
	public function delete_productAction($id, $delete_product_id) {
		$delete_form = $this->createDeleteForm ( $delete_product_id );
		$request = $this->getRequest ();
		
		$delete_form->bind ( $request );
		
		if ($delete_form->isValid ()) {
			$em = $this->getDoctrine ()->getEntityManager ();
			$entity = $em->getRepository ( 'CRMSaleBundle:SaleOrderProduct' )->find ( $delete_product_id );
			
			if (! $entity) {
				throw $this->createNotFoundException ( 'Unable to find Order entity.' );
			}
			
			$em->remove ( $entity );
			$em->flush ();
			
			$this->getSaleActivity ( "creation_user deleted product: " . $entity->getProductName () . " in " . $id, "Order", $id );
		}
		
		return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_order_add_product', array (
				'id' => $id,
				'product_id' => '0',
				'delete_product_id' => $delete_product_id 
		) ) );
	}
	private function createDeleteForm($id) {
		return $this->createFormBuilder ( array (
				'id' => $id 
		) )->add ( 'id', 'hidden' )->getForm ();
	}
	public function getProdAmount($id, $em, $field) {
		// Get total discount of products inserted based on provided id.
		$total = $em->createQueryBuilder ()->select ( 'sum(mf.' . $field . ')' )->from ( 'CRMSaleBundle:SaleOrderProduct', 'mf' )->where ( 'mf.orderDBId = :Id' )->setParameter ( 'Id', $id )->getQuery ()->getSingleScalarResult ();
		return $total;
	}
	public function getQuoteFieldValue($id, $em, $field) {
		$total = $em->createQueryBuilder ()->select ( 'sum(mf.totalShipping)' )->from ( 'CRMSaleBundle:SaleOrder', 'mf' )->where ( 'mf.id = :id' )->setParameter ( 'id', $id )->getQuery ()->getSingleScalarResult ();
		return $total;
	}
	public function getSaleActivity($activiydesc, $salemodule, $quotedb_id) {
		$activity = new SaleActivity ();
		$form1 = $this->get ( 'form.factory' )->create ( new SaleActivityType (), $activity );
		$request1 = $this->get ( 'request' );
		
		if ($request1->getMethod () == 'POST') {
			$form1->bind ( $request1 );
			$activity->setActivityDesc ( $activiydesc );
			$activity->setDateAdded ( new \DateTime () );
			$activity->setActivityUser ( $this->getUser ()->getUsername () );
			$activity->setSaleModule ( $salemodule );
			$activity->setQuoteDBId ( $quotedb_id );
			$em1 = $this->get ( 'doctrine' )->getManager ();
			$em1->persist ( $activity );
			$em1->flush ();
		}
	}
	public function createprodAction() {
		$this->getTheme ();
		$product = new Product ();
		$form_product = $this->get ( 'form.factory' )->create ( new ProductType ( $this->getUser ()->getUsername () ), $product );
		$request = $this->get ( 'request' );
		if ($request->getMethod () == 'POST') {
			
			$form_product->bind ( $request );
			if ($form_product->isValid ()) {
				
				$em = $this->get ( 'doctrine' )->getManager ();
				$product->setCreationUser ( $this->getUser ()->getUsername () );
				$product->setDateAdded ( new \DateTime () );
				// $paytype = $em->getRepository('CRMSaleBundle:PayTerm')->find($product->getPricetype());
				// $product->setPricetype($paytype->getId());
				
				$em->persist ( $product );
				$em->flush ();
			}
		}
		
		return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_sale_order_create' ) );
	}
	public function getUserActivity($activiydesc) {
		$user_activity = new UserActivity ();
		
		$form1 = $this->get ( 'form.factory' )->create ( new UserActivityType (), $user_activity );
		
		$request = $this->get ( 'request' );
		
		if ($request->getMethod () == 'POST') {
			$form1->bind ( $request );
			
			$user_activity->setActivityDesc ( $activiydesc );
			$user_activity->setDateAdded ( new \DateTime () );
			$user_activity->setModule ( 'Order' );
			$user_activity->setActivityUser ( $this->getUser ()->getUsername () );
			$em = $this->get ( 'doctrine' )->getManager ();
			$em->persist ( $user_activity );
			$em->flush ();
		}
	}
	public function invoiceAction($id) {
		$repository = $this->getDoctrine ()->getRepository ( 'CRMUserBundle:GlobalParameter' );
		$prepend = $repository->createQueryBuilder ( 'p' )->where ( 'p.parameterCode = :a' )->andWhere ( 'p.creationUser = :u' )->setParameter ( 'a', 'INVOICE_NO' )->setParameter ( 'u', $this->getUser ()->getUsername () )->getQuery ()->getOneOrNullResult ();
		
		if (! $prepend) {
			$invoice_prepend = "0";
		} else {
			$invoice_prepend = $prepend->getParameterValue ();
		}
		
		$em = $this->getDoctrine ()->getManager ();
		
		$order = $em->getRepository ( 'CRMSaleBundle:SaleOrder' )->find ( $id );
		
		$invoice = new Invoice ();
		$datetime = new \DateTime ( "now" );
		$d = $datetime->format ( 'ymdh' );
		
		$invoice->setInvoiceNumber ( $invoice_prepend . $d );
		$invoice->setOrderReference ( $order->getOrderReference () );
		$invoice->setQuoteId ( $order->getQuoteId () );
		$invoice->setCreationDate ( new \DateTime () );
		$invoice->setCreationUser ( $this->getUser ()->getUsername () );
		$invoice->setInvoiceDate ( new \DateTime () );
		$invoice->setPoOrder ( $order->getPoOrder () );
		$invoice->setCustomerNumber ( $order->getCustomerNumber () );
		$invoice->setCustomerContactId ( $order->getCustomerContactId () );
		$invoice->setInvoiceCustomerName ( $order->getCustomerName () );
		$invoice->setInvoicePerson ( $order->getContactPerson () );
		$invoice->setInvoiceStreet ( $order->getCustomerStreet () );
		$invoice->setInvoiceCity ( $order->getCustomerCity () );
		$invoice->setInvoiceState ( $order->getCustomerState () );
		$invoice->setInvoiceCountry ( $order->getCustomerCountry () );
		$invoice->setInvoiceEmail ( $order->getCustomerEmail () );
		$invoice->setInvoiceFax ( $order->getCustomerFax () );
		$invoice->setInvoiceMobile ( $order->getCustomerMobile () );
		$invoice->setInvoicePhone ( $order->getCustomerPhone () );
		$invoice->settotalShipping ( '0' );
		$invoice->setShippingMethod ( $order->getShippingMethod () );
		$invoice->setDeliveryDate ( $order->getDeliveryDate () );
		$invoice->setTotalDiscount ( $order->getTotalDiscount () );
		$invoice->setTotalSurcharge ( $order->getTotalSurcharge () );
		$invoice->setamountDue ( $order->getamountDue () );
		$invoice->setSubtotal ( $order->getSubtotal () );
		$invoice->setAmountCurrency ( $this->getCurrency () );
		$invoice->setSalesRep ( $order->getSalesRep () );
		$invoice->setNotes ( $order->getNotes () );
		$invoice->setInvoiceStatus ( 'Draft' );
		$em->persist ( $invoice );
		$em->flush ();
		
		$update_invoice = $em->getRepository ( 'CRMSaleBundle:Invoice' )->find ( $invoice->getId () );
		$update_invoice->setOrderReference ( $invoice_prepend . $d . $invoice->getId () );
		$em->persist ( $update_invoice );
		$em->flush ();
		
		// Show list of product added during quote creation.
		$products = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:SaleOrderProduct', 'b' )->where ( 'b.orderDBId = :orderDBId' )->setParameter ( 'orderDBId', $id )->addOrderBy ( 'b.productId', 'ASC' )->getQuery ()->getResult ();
		
		$invoice_product = new InvoiceProduct ();
		foreach ( $products as $prod ) {
			$invoice_product->setQuoteId ( $prod->getQuoteId () );
			$invoice_product->setOrderDBId ( $order->getId () );
			$invoice_product->setProductId ( $prod->getProductId () );
			$invoice_product->setProductCode ( $prod->getProductCode () );
			$invoice_product->setProductName ( $prod->getProductName () );
			$invoice_product->setProductPrice ( $prod->getProductPrice () );
			$invoice_product->setPricetype ( $prod->getPricetype () );
			$invoice_product->setDiscount ( $prod->getDiscount () );
			$invoice_product->setSurcharge ( $prod->getSurcharge () );
			$invoice_product->setQuantity ( $prod->getQuantity () );
			$invoice_product->setAmount ( $prod->getAmount () );
			$invoice_product->setCreationUser ( $this->getUser ()->getUsername () );
			$invoice_product->setCreationDate ( new \DateTime () );
			$em->persist ( $invoice_product );
			$em->flush ();
		}
		
		// $this->getUserActivity("creation_user created new order from: " . $order_product->getQuoteId());
		
		return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_invoice_edit', array (
				'id' => $invoice->getId () 
		) ) );
	}
	public function getCurrency() {
		$repository = $this->getDoctrine ()->getRepository ( 'CRMUserBundle:GlobalParameter' );
		
		$currency = $repository->createQueryBuilder ( 'p' )->where ( 'p.parameterCode = :a' )->andWhere ( 'p.creationUser = :u' )->setParameter ( 'a', 'CURRENCY' )->setParameter ( 'u', $this->getUser ()->getUsername () )->getQuery ()->getOneOrNullResult ();
		
		if (! $currency) {
			$curr = "USD";
		} else {
			$curr = $currency->getParameterValue ();
		}
		
		return $curr;
	}
	public function getTheme() {
		$request = $this->getRequest ();
		if ($request->query->get ( 'skin' )) {
			$em = $this->getDoctrine ()->getManager ();
			$user = $em->getRepository ( 'CRMUserBundle:User' )->find ( $this->getUser ()->getId () );
			$user->setTheme ( $request->query->get ( 'skin' ) );
			$em->flush ();
		}
	}
	
	public function countStatus($field) {
		$em = $this->getDoctrine ()->getManager ();
		$c_qry = $em->createQueryBuilder ()
		->select ( 'count(c.id)' )
		->from ( 'CRMSaleBundle:SaleOrder', 'c' )
		->where ( 'c.creationUser = :id' )
		->andWhere ( 'c.orderStatus = :s' )
		->setParameter ( 'id', $this->getUser ()->getUserName () )
		->setParameter ( 's', ''.$field.'' )
		->getQuery ()->getSingleScalarResult ();
			
		return $c_qry;
	} 
	
	 
}
