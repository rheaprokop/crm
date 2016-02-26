<?php

/*
 * This software belongs to Rhea Software S.R.O.
 * Any other information are specified in the software contract agreement.
 */
namespace CRM\SaleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
// Import namespaces for Create contact properties
use CRM\SaleBundle\Entity\Quote;
use CRM\SaleBundle\Entity\Product;
use CRM\SaleBundle\Entity\SaleOrder;
use CRM\SaleBundle\Entity\QuoteProduct;
use CRM\SaleBundle\Entity\SaleActivity;
use CRM\SaleBundle\Entity\SaleOrderProduct;
use CRM\SaleBundle\Form\ProductType;
use CRM\SaleBundle\Form\QuoteProductType;
use CRM\SaleBundle\Form\QuoteType;
use CRM\SaleBundle\Form\QuoteAmountType;
use CRM\SaleBundle\Form\QuoteStatusType;
use CRM\SaleBundle\Form\SaleActivityType;
use CRM\UserBundle\Entity\UserActivity;
use CRM\UserBundle\Form\UserActivityType;

class QuoteController extends Controller {
	public function createAction() {
		$this->getTheme ();
		
		$em = $this->getDoctrine ()->getManager ();
		
		$contacts = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMContactBundle:Contact', 'b' )->addOrderBy ( 'b.firstname', 'ASC' )->where ( 'b.creation_user = :id' )->setParameter ( 'id', $this->getUser ()->getUsername () )->getQuery ()->getResult ();
		
		$accounts = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMContactBundle:Account', 'b' )->addOrderBy ( 'b.name', 'ASC' )->where ( 'b.creation_user = :id' )->setParameter ( 'id', $this->getUser ()->getUsername () )->getQuery ()->getResult ();
		
		$quote = new Quote ();
		$form = $this->get ( 'form.factory' )->create ( new QuoteType (), $quote );
		$request = $this->get ( 'request' );
		if ($request->getMethod () == 'POST') {
			$form->bind ( $request );
			if ($form->isValid ()) {
				$em = $this->get ( 'doctrine' )->getManager ();
				$quote->setCreatedDate ( new \DateTime () );
				$quote->setCreationUser ( $this->getUser ()->getUsername () );
				$quote->setQuoteId ( "Draft" );
				$quote->setQuoteStatus ( "Draft" );
				$quote->setAmountCurrency ( $this->getCurrency () );
				// to chnage to actual expiration data based on user input
				// $quote->setExpirationDate(new \DateTime());
				$em->persist ( $quote );
				$em->flush ();
				
				$datetime = new \DateTime ( "now" );
				$d = $datetime->format ( 'ymdh' );
				
				$update_quote = $em->getRepository ( 'CRMSaleBundle:Quote' )->find ( $quote->getId () );
				$update_quote->setQuoteId ( $this->getQuoteIdParam () . $d . $quote->getId () );
				$em->persist ( $update_quote );
				$em->flush ();
				
				$this->getSaleActivity ( "creation_user created new quotation: " . $quote->getQuoteId (), "Quote", $quote->getId () );
				
				$this->getUserActivity ( "creation_user created new deal: " . $quote->getQuoteId () );
				
				$this->get ( 'session' )->getFlashBag ()->set ( 'notice', 'You have successfully added ' . $quote->getQuoteName () . ' to the database!' );
				
				// get first id in product
				// This page will redirect to View Profile.
				return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_quote_add_product', array (
						'id' => $quote->getId (),
						'quote_id' => $quote->getQuoteId (),
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
		
		return $this->render ( 'CRMSaleBundle:Quote:create.html.twig', array (
				'contacts' => $contacts,
				'accounts' => $accounts,
				'sale_person' => $this->getUser ()->getUsername (),
				'form' => $form->createView (),
				'form_product' => $form_product->createView () 
		) );
	}
	
	/**
	 * @Route("/{id}/update/amount", name="CRMSaleBundle_quote_update_amt_prod")
	 * @Method("post")
	 * @Template("CRMSaleBundle:Quote:addproduct.html.twig")
	 */
	public function updateamountAction($id, $quote_id) {
		$this->getTheme ();
		$em = $this->getDoctrine ()->getManager ();
		$entity = $em->getRepository ( 'CRMSaleBundle:Quote' )->find ( $id );
		
		if (! $entity) {
			throw $this->createNotFoundException ( 'Unable to find Quote entity.' );
		}
		
		$edit_form = $this->createForm ( new QuoteAmountType ( $em, '0' ), $entity );
		$request = $this->getRequest ();
		$edit_form->bind ( $request );
		
		if ($edit_form->isValid ()) {
			$em->persist ( $entity );
			$em->flush ();
			
			return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_quote_view', array (
					'id' => $id,
					'quote_id' => $quote_id,
					'status' => '0' 
			) ) );
		}
	}
	public function addproductAction($id, $quote_id, $delete_product_id, $product_id) {
		$this->getTheme ();
		$repository = $this->getDoctrine ()->getRepository ( 'CRMUserBundle:GlobalParameter' );
		
		$currency = $repository->createQueryBuilder ( 'p' )->where ( 'p.parameterCode = :a' )->andWhere ( 'p.creationUser = :u' )->setParameter ( 'a', 'CURRENCY' )->setParameter ( 'u', $this->getUser ()->getUsername () )->getQuery ()->getOneOrNullResult ();
		
		if (! $currency) {
			$curr = "USD";
		} else {
			$curr = $currency->getParameterValue ();
		}
		$em = $this->getDoctrine ()->getManager ();
		
		$quote = $em->getRepository ( 'CRMSaleBundle:Quote' )->find ( $id );
		
		if ($product_id == "0") {
			$query = $em->createQuery ( 'SELECT u.id FROM CRMSaleBundle:Product u WHERE u.creationUser = \'' . $this->getUser ()->getUsername () . '\'' );
			$users = $query->getResult (); // array of CmsUser username and name values
			$productid = $users [0] ['id'];
			$productlist = $em->getRepository ( 'CRMSaleBundle:Product' )->find ( $productid );
			$product_id = '0';
		} else {
			$productlist = $em->getRepository ( 'CRMSaleBundle:Product' )->find ( $product_id );
		}
		
		$current_del_id = $delete_product_id;
		$deleteproductForm = $this->createDeleteForm ( $delete_product_id );
		
		$quoteproduct = new QuoteProduct ();
		$form = $this->get ( 'form.factory' )->create ( new QuoteProductType ( $em, $product_id, $this->getUser ()->getUsername () ), $quoteproduct );
		$edit_form = $this->createForm ( new QuoteAmountType (), $quote );
		
		$request = $this->get ( 'request' );
		
		// Show list of product added during quote creation.
		$products = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:QuoteProduct', 'b' )->where ( 'b.quoteDBId = :quoteDBId' )->andWhere ( 'b.creationUser = :u' )->setParameter ( 'quoteDBId', $id )->setParameter ( 'u', $this->getUser ()->getUsername () )->addOrderBy ( 'b.productId', 'ASC' )->getQuery ()->getResult ();
		
		// $payterms = $em->createQueryBuilder()
		// ->select('b')
		// ->from('CRMSaleBundle:PayTerm', 'b')
		// ->getQuery()
		// ->getResult();
		
		if ($request->getMethod () == 'POST') {
			$form->bind ( $request );
			if ($form->isValid ()) {
				
				$quoteproduct->setQuoteDBId ( $id );
				$quoteproduct->setQuoteId ( $quote_id );
				$product = $em->getRepository ( 'CRMSaleBundle:Product' )->find ( $quoteproduct->getProductName () );
				$quoteproduct->setProductId ( $product->getId () );
				$quoteproduct->setProductCode ( $product->getProductCode () );
				$quoteproduct->setProductPrice ( $product->getPrice () );
				$quoteproduct->setPricetype ( $product->getPricetype () );
				// compute amount for each product added and save it to db
				// $percentage = 50;
				// $totalWidth = 350;
				//
				// $new_width = ($percentage / 100) * $totalWidth;
				$total_product = $quoteproduct->getProductPrice () * $quoteproduct->getQuantity ();
				$discount = (($quoteproduct->getDiscount () / 100) * $total_product);
				$surcharge = (($quoteproduct->getSurcharge () / 100) * $total_product);
				$quoteproduct->setAmount ( ($total_product - $discount) + $surcharge ); // add surcharge
				$quoteproduct->setCreationUser ( $this->getUser ()->getUsername () );
				$quoteproduct->setCreationDate ( new \DateTime () );
				$em = $this->get ( 'doctrine' )->getManager ();
				$em->persist ( $quoteproduct );
				$em->flush ();
				
				$this->getSaleActivity ( "creation_user added product: " . $quoteproduct->getProductName () . " for " . $quote_id, "Quote", $id );
				
				$this->getUserActivity ( "creation_user added new product: " . $quoteproduct->getProductName () . " on " . $quote_id, "Quote", $id );
				
				return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_quote_add_product', array (
						'id' => $id,
						'quote_id' => $quote_id,
						'product_id' => $productlist->getId (),
						'delete_product_id' => '0' 
				) ) );
			}
		}
		
		// display payterms table.
		// display payterms table.
		$query = $em->createQuery ( 'SELECT SUM( a.quantity ) total_quantity, a.pricetype, SUM( a.productprice ) total_price
                    FROM CRMSaleBundle:QuoteProduct a WHERE a.quoteDBId = \'' . $id . '\'' );
		
		$payterms_total = $query->getResult ();
		
		return $this->render ( 'CRMSaleBundle:Quote:addproduct.html.twig', array (
				'products' => $products,
				'currency' => $curr,
				'quote' => $quote,
				'product_id' => $productlist,
				'payterms' => '0',
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
	public function manageAction() {
		$this->getTheme ();
		$em = $this->getDoctrine ()->getManager ();
		
		$count = $em->createQueryBuilder ()->select ( 'count(quote.id)' )->from ( 'CRMSaleBundle:Quote', 'quote' )->where ( 'quote.creationUser = :user' )->setParameter ( 'user', $this->getUser ()->getUsername () )->getQuery ()->getSingleScalarResult ();
		
		$quotes = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:Quote', 'b' )->where ( 'b.creationUser = :user' )->setParameter ( 'user', $this->getUser ()->getUsername () )->addOrderBy ( 'b.quoteName', 'ASC' )->getQuery ()->getResult ();
		
		// Populate recent contact for widget
		$offset = 0;
		$limit = 1;
		$quote_recent = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:Quote', 'b' )->where ( 'b.creationUser = :user' )->setParameter ( 'user', $this->getUser ()->getUsername () )->addOrderBy ( 'b.createdDate', 'DESC' )->setFirstResult ( $offset )->setMaxResults ( $limit )->getQuery ()->getResult ();
		
		$prepared = $em->createQueryBuilder ()->select ( 'count(s.id)' )->from ( 'CRMSaleBundle:Quote', 's' )->where ( 's.creationUser = :id' )->andWhere ( 's.quoteStatus = :stat' )->setParameter ( 'id', $this->getUser ()->getUsername () )->setParameter ( 'stat', 'Prepared' )->getQuery ()->getSingleScalarResult ();
	 		
	 	$sent_to_customer = $em->createQueryBuilder ()->select ( 'count(s.id)' )->from ( 'CRMSaleBundle:Quote', 's' )->where ( 's.creationUser = :id' )->andWhere ( 's.quoteStatus = :stat' )->setParameter ( 'id', $this->getUser ()->getUsername () )->setParameter ( 'stat', 'Waiting' )->getQuery ()->getSingleScalarResult ();
		
		return $this->render ( 'CRMSaleBundle:Quote:manage.html.twig', array (
				'quotes' => $quotes,
				'quote_recent' => $quote_recent,
				// 'categories' => $categories,
				'count_won' => $this->countWon (),
				'count_loss' => $this->countLoss (),
				'prepared' => $prepared,
				'sent_to_customer' => $sent_to_customer,
				'count' => $count 
		) );
	}
	public function quoteAction($id, $quote_id, $status) {
		$this->getTheme ();
		$em = $this->getDoctrine ()->getManager ();
		$quote = $em->getRepository ( 'CRMSaleBundle:Quote' )->find ( $id );
		
		if ($status != 0) {
			
			switch ($status) {
				case '4f8ebbe84c8' :
					$status = "Prepared";
					break;
				case '5706de961fb' :
					$status = "Waiting";
					break;
				case '19abd416eb9' :
					$status = "Won";
					break;
				case '14781ee5e85' :
					$status = "Loss";
					break;
				default :
					$status = "Draft";
					break;
			}
			$query = $em->createQuery ( 'UPDATE CRMSaleBundle:Quote u SET u.quoteStatus = \'' . $status . '\' WHERE u.id = \'' . $id . '\'' );
			
			$query->getResult ();
			$edit_form = $this->get ( 'form.factory' )->create ( new QuoteStatusType ( $status ), $quote );
		} elseif ($status == "" || $status == 0) {
			$edit_form = $this->get ( 'form.factory' )->create ( new QuoteStatusType ( $quote->getQuoteStatus () ), $quote );
		} else {
			$edit_form = $this->get ( 'form.factory' )->create ( new QuoteStatusType ( $quote->getQuoteStatus () ), $quote );
		}
		
		// View list of product added during quote creation
		$productslist = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:QuoteProduct', 'b' )->where ( 'b.quoteId = :quoteId' )->setParameter ( 'quoteId', $quote_id )->addOrderBy ( 'b.productId', 'ASC' )->getQuery ()->getResult ();
		
		// show recent activities (5)
		// Populate recent contact for widget
		$offset = 0;
		$limit = 10;
		$recent_activities = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:SaleActivity', 'b' )->addOrderBy ( 'b.dateAdded', 'DESC' )->where ( 'b.quoteDBId = :id' )->setParameter ( 'id', $id )->setFirstResult ( $offset )->setMaxResults ( $limit )->getQuery ()->getResult ();
		
		// display payterms table.
		$query = $em->createQuery ( 'SELECT SUM( b.quantity ) total_quantity, a.name, SUM( b.productprice ) total_price
                    FROM CRMSaleBundle:PayTerm a
                    INNER JOIN CRMSaleBundle:QuoteProduct b WITH a.id = b.pricetype
                    WHERE b.id =  ' . $id . '
                        GROUP BY a.id' );
		
		$payterms_total = $query->getResult ();
		
		$deleteForm = $this->createDeleteForm ( $id );
		return $this->render ( 'CRMSaleBundle:Quote:quote.html.twig', array (
				'productslist' => $productslist,
				'quote' => $quote,
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
	
	/**
	 * Deletes from Quote entity.
	 *
	 * @Route("/{id}/delete", name="CRMSaleBundle_quote_delete")
	 * @Method("post")
	 */
	public function deleteAction($id) {
		$delete_form = $this->createDeleteForm ( $id );
		$request = $this->getRequest ();
		
		$delete_form->bind ( $request );
		
		if ($delete_form->isValid ()) {
			$em = $this->getDoctrine ()->getEntityManager ();
			$entity = $em->getRepository ( 'CRMSaleBundle:Quote' )->find ( $id );
			
			if (! $entity) {
				throw $this->createNotFoundException ( 'Unable to find Quote entity.' );
			}
			
			$em->remove ( $entity );
			$em->flush ();
			
			$this->getUserActivity ( "creation_user deleted a deal: " . $entity->getQuoteId () );
			
			$query = $em->createQuery ( 'DELETE CRMSaleBundle:QuoteProduct c WHERE c.quoteDBId = ' . $id . '' );
			$query->execute ();
			
			$this->getSaleActivity ( "creation_user deleted quotation: " . $entity->getQuoteId (), "Quote", $id );
		}
		
		return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_quote_manage' ) );
	}
	public function delete_productAction($id, $quote_id, $delete_product_id) {
		$delete_form = $this->createDeleteForm ( $delete_product_id );
		$request = $this->getRequest ();
		
		$delete_form->bind ( $request );
		
		if ($delete_form->isValid ()) {
			$em = $this->getDoctrine ()->getEntityManager ();
			$entity = $em->getRepository ( 'CRMSaleBundle:QuoteProduct' )->find ( $delete_product_id );
			
			if (! $entity) {
				throw $this->createNotFoundException ( 'Unable to find Order entity.' );
			}
			
			$em->remove ( $entity );
			$em->flush ();
			
			$this->getSaleActivity ( "creation_user deleted product: " . $entity->getProductName () . " in " . $quote_id, "Quote", $id );
		}
		
		return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_quote_add_product', array (
				'id' => $id,
				'quote_id' => $quote_id,
				'product_id' => '0',
				'delete_product_id' => $delete_product_id 
		) ) );
	}
	private function createDeleteForm($id) {
		return $this->createFormBuilder ( array (
				'id' => $id 
		) )->add ( 'id', 'hidden' )->getForm ();
	}
	
	/**
	 * This forms display the data and will call updateAction to continually update the provided $id
	 *
	 * @Route("/{id}/edit", name="CRMSaleBundle_quote_edit")
	 * @Template()
	 */
	public function editAction($id, $quote_id) {
		$this->getTheme ();
		$em = $this->getDoctrine ()->getManager ();
		
		$entity = $em->getRepository ( 'CRMSaleBundle:Quote' )->find ( $id );
		
		if (! $entity) {
			throw $this->createNotFoundException ( 'Unable to find Order entity.' );
		}
		
		$edit_form = $this->createForm ( new QuoteType (), $entity );
		$deleteForm = $this->createDeleteForm ( $id );
		
		$contacts = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMContactBundle:Contact', 'b' )->addOrderBy ( 'b.firstname', 'ASC' )->where ( 'b.creation_user = :id' )->setParameter ( 'id', $this->getUser ()->getUsername () )->getQuery ()->getResult ();
		
		$accounts = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMContactBundle:Account', 'b' )->addOrderBy ( 'b.name', 'ASC' )->where ( 'b.creation_user = :id' )->setParameter ( 'id', $this->getUser ()->getUsername () )->getQuery ()->getResult ();
		
		return array (
				'entity' => $entity,
				'accounts' => $accounts,
				'contacts' => $contacts,
				'edit_form' => $edit_form->createView (),
				'delete_form' => $deleteForm->createView () 
		);
	}
	
	/**
	 * Edits an existing Quote entity.
	 *
	 * @Route("/{id}/update", name="CRMSaleBundle_quote_edit")
	 * @Method("post")
	 * @Template("CRMSaleBundle:Quote:edit.html.twig")
	 */
	public function updateAction($id, $quote_id) {
		$em = $this->getDoctrine ()->getManager ();
		$entity = $em->getRepository ( 'CRMSaleBundle:Quote' )->find ( $id );
		if (! $entity) {
			throw $this->createNotFoundException ( 'Unable to find Quote entity.' );
		}
		
		$edit_form = $this->createForm ( new QuoteType (), $entity );
		$request = $this->getRequest ();
		$edit_form->bind ( $request );
		
		if ($edit_form->isValid ()) {
			$em->persist ( $entity );
			$em->flush ();
			
			$this->getUserActivity ( "creation_user updated deal: " . $entity->getQuoteId () );
			
			return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_quote_add_product', array (
					'id' => $entity->getId (),
					'quote_id' => $entity->getQuoteId (),
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
	public function getProdAmount($id, $em, $field) {
		// Get total discount of products inserted based on provided id.
		$total = $em->createQueryBuilder ()->select ( 'sum(mf.' . $field . ')' )->from ( 'CRMSaleBundle:QuoteProduct', 'mf' )->where ( 'mf.quoteDBId = :quoteDBId' )->setParameter ( 'quoteDBId', $id )->getQuery ()->getSingleScalarResult ();
		return $total;
	}
	public function getQuoteFieldValue($id, $em, $field) {
		$total = $em->createQueryBuilder ()->select ( 'sum(mf.totalShipping)' )->from ( 'CRMSaleBundle:Quote', 'mf' )->where ( 'mf.id = :id' )->setParameter ( 'id', $id )->getQuery ()->getSingleScalarResult ();
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
	public function printAction($id) {
		$this->getTheme ();
		$em = $this->getDoctrine ()->getManager ();
		$quote = $em->getRepository ( 'CRMSaleBundle:Quote' )->find ( $id );
		
		// View list of product added during quote creation
		$productslist = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:InvoiceProduct', 'b' )->where ( 'b.invoiceDBId = :invoiceId' )->setParameter ( 'invoiceId', $id )->addOrderBy ( 'b.productId', 'ASC' )->getQuery ()->getResult ();
		
		// show recent activities (5)
		// Populate recent contact for widget
		$offset = 0;
		$limit = 10;
		$recent_activities = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:SaleActivity', 'b' )->addOrderBy ( 'b.dateAdded', 'DESC' )->where ( 'b.quoteDBId = :id' )->setParameter ( 'id', $id )->setFirstResult ( $offset )->setMaxResults ( $limit )->getQuery ()->getResult ();
		
		// display payterms table.
		$query = $em->createQuery ( 'SELECT SUM( b.quantity ) total_quantity, a.abbrev, SUM( b.productprice ) total_price
                    FROM CRMSaleBundle:PayTerm a
                    INNER JOIN CRMSaleBundle:InvoiceProduct b WITH a.id = b.pricetype
                    WHERE b.id =  ' . $id . '
                        GROUP BY a.id' );
		
		$payterms_total = $query->getResult ();
		
		$deleteForm = $this->createDeleteForm ( $id );
		return $this->render ( 'CRMSaleBundle:Quote:print.html.twig', array (
				'productslist' => $productslist,
				'quote' => $quote,
				'payterms' => $payterms_total,
				'total_discount' => $this->getProdAmount ( $id, $em, 'discount' ),
				'total_surcharge' => $this->getProdAmount ( $id, $em, 'surcharge' ),
				'total_shipping' => $this->getQuoteFieldValue ( $id, $em, 'amountDue' ),
				'total_vat' => $this->getQuoteFieldValue ( $id, $em, 'totalVat' ),
				'delete_form' => $deleteForm->createView () 
		) );
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
		
		return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_quote_create' ) );
	}
	public function orderAction($id) {
		$this->getTheme ();
		
		$repository = $this->getDoctrine ()->getRepository ( 'CRMUserBundle:GlobalParameter' );
		$prepend = $repository->createQueryBuilder ( 'p' )->where ( 'p.parameterCode = :a' )->andWhere ( 'p.creationUser = :u' )->setParameter ( 'a', 'ORDER_REF' )->setParameter ( 'u', $this->getUser ()->getUsername () )->getQuery ()->getOneOrNullResult ();
		
		if (! $prepend) {
			$quote_prepend = "0";
		} else {
			$quote_prepend = $prepend->getParameterValue ();
		}
		
		$cust_prepend = $repository->createQueryBuilder ( 'p' )->where ( 'p.parameterCode = :a' )->andWhere ( 'p.creationUser = :u' )->setParameter ( 'a', 'CUST_NO' )->setParameter ( 'u', $this->getUser ()->getUsername () )->getQuery ()->getOneOrNullResult ();
		
		if (! $cust_prepend) {
			$cprepend = "0";
		} else {
			$cprepend = $cust_prepend->getParameterValue ();
		}
		
		$em = $this->getDoctrine ()->getManager (); 
		
		$quote = $em->getRepository ( 'CRMSaleBundle:Quote' )->find ( $id );
		if ($quote->getQuoteStatus () == "Draft") {
			$this->get ( 'session' )->getFlashBag ()->set ( 'notice_status_draft', 'error' );
			return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_quote_view', array (
					'id' => $quote->getId (),
					'quote_id' => $quote->getQuoteId (),
					'status' => '0' 
			) ) );
		} 
		
		if ($quote->getQuoteStatus () == "Loss") {
			
			$quote->setQuoteStatus ( "Won" );
			$em->persist ( $quote );
			$em->flush ();
		}
		
		$order = new SaleOrder ();
		$datetime = new \DateTime ( "now" );
		$d = $datetime->format ( 'ymdh' );
		
		$order->setQuoteId ( $quote->getQuoteId () );
		$order->setCreationDate ( new \DateTime () );
		$order->setDeliveryDate ( new \DateTime () );
		$order->setCreationUser ( $this->getUser ()->getUsername () );
		$order->setOrderDate ( new \DateTime () );
		$order->setCustomerName ( $quote->getAccountName () );
		$order->setCustomerNumber ( $cprepend . $d );
		$order->setContactPerson ( $quote->getContactPerson () );
		$order->setCustomerStreet ( $quote->getCustomerStreet () );
		$order->setCustomerCity ( $quote->getCustomerCity () );
		$order->setCustomerState ( $quote->getCustomerState () );
		$order->setCustomerCountry ( $quote->getCustomerCountry () );
		$order->setCustomerFax ( $quote->getCustomerFax () );
		$order->setCustomerMobile ( $quote->getCustomerMobile () );
		$order->setCustomerPhone ( $quote->getContactPhone () );
		$order->setCustomerEmail ( $quote->getContactEmail () );
		$order->setSalesRep ( $quote->getAccountManager () );
		$order->setShippingMethod ( 'Free' );
		$order->setNotes ( $quote->getAdditionalNotes () );
		$order->setTotalDiscount ( $quote->getTotalDiscount () );
		$order->setTotalSurcharge ( $quote->getTotalSurcharge () );
		$order->setTotalVat ( $quote->getTotalVat () );
		$order->setSubtotal ( $quote->getSubtotal () );
		$order->setamountDue ( $quote->getAmountDue () );
		$order->setOrderReference ( $quote_prepend . $d );
		$order->setOrderStatus ( 'Draft' );
		$order->setAmountCurrency ( $this->getCurrency () );
		$em->persist ( $order );
		$em->flush ();
		
		$update_order = $em->getRepository ( 'CRMSaleBundle:SaleOrder' )->find ( $order->getId () );
		$update_order->setOrderReference ( $quote_prepend . $d . $order->getId () );
		$update_order->setCustomerNumber ( $order->getCustomerNumber () . $order->getId () );
		$em->persist ( $update_order );
		$em->flush ();
		
		// Show list of product added during quote creation.
		$products = $em->createQueryBuilder ()->select ( 'b' )->from ( 'CRMSaleBundle:QuoteProduct', 'b' )->where ( 'b.quoteDBId = :quoteDBId' )->setParameter ( 'quoteDBId', $id )->addOrderBy ( 'b.productId', 'ASC' )->getQuery ()->getResult ();
		
		$order_product = new SaleOrderProduct ();
		foreach ( $products as $product ) {
			$order_product->setQuoteId ( $product->getQuoteId () );
			$order_product->setOrderDBId ( $order->getId () );
			$order_product->setProductId ( $product->getProductId () );
			$order_product->setProductCode ( $product->getProductCode () );
			$order_product->setProductName ( $product->getProductName () );
			$order_product->setProductPrice ( $product->getProductPrice () );
			$order_product->setPricetype ( $product->getPricetype () );
			$order_product->setDiscount ( $product->getDiscount () );
			$order_product->setSurcharge ( $product->getSurcharge () );
			$order_product->setQuantity ( $product->getQuantity () );
			$order_product->setAmount ( $product->getAmount () );
			$order_product->setCreationUser ( $this->getUser ()->getUsername () );
			$order_product->setCreationDate ( new \DateTime () );
			$em->persist ( $order_product );
			$em->flush ();
		}
		
		$this->getUserActivity ( "creation_user created new order from: " . $quote->getQuoteId () );
		
		$deal_prod = $em->createQueryBuilder()
		->select('count(c.id)')
		->from('CRMSaleBundle:QuoteProduct', 'c')
		->where('c.creationUser = :u')
		->andWhere('c.quoteDBId = :id')
		->setParameter('u', $this->getUser()->getUsername())
		->setParameter('id', $id)
		->getQuery()->getSingleScalarResult();
		
		if($deal_prod == 0)
		{
			$this->get ( 'session' )->getFlashBag ()->set ( 'notice_status_no_product', 'error' );
		}
		else
		{
			$this->get ( 'session' )->getFlashBag ()->set ( 'notice_created_order_from_quote', 'success' );
		}
		
		return $this->redirect ( $this->generateUrl ( 'CRMSaleBundle_order_edit', array (
				'id' => $order->getId () 
		) ) );
	}
	public function getUserActivity($activiydesc) {
		$user_activity = new UserActivity ();
		
		$form1 = $this->get ( 'form.factory' )->create ( new UserActivityType (), $user_activity );
		
		$request = $this->get ( 'request' );
		
		if ($request->getMethod () == 'POST') {
			$form1->bind ( $request );
			
			$user_activity->setActivityDesc ( $activiydesc );
			$user_activity->setDateAdded ( new \DateTime () );
			$user_activity->setModule ( 'Quote' );
			$user_activity->setActivityUser ( $this->getUser ()->getUsername () );
			$em = $this->get ( 'doctrine' )->getManager ();
			$em->persist ( $user_activity );
			$em->flush ();
		}
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
	public function getQuoteIdParam() {
		$repository = $this->getDoctrine ()->getRepository ( 'CRMUserBundle:GlobalParameter' );
		$quote_id_prepend = $repository->createQueryBuilder ( 'p' )->where ( 'p.parameterCode = :a' )->andWhere ( 'p.creationUser = :u' )->setParameter ( 'a', 'QUOTE_ID' )->setParameter ( 'u', $this->getUser ()->getUsername () )->getQuery ()->getOneOrNullResult ();
		
		if (! $quote_id_prepend) {
			$quote_prepend = "0";
		} else {
			$quote_prepend = $quote_id_prepend->getParameterValue ();
		}
		
		return $quote_prepend;
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
	public function countWon() {
		$em = $this->getDoctrine ()->getManager ();
		$won_deal = $em->createQueryBuilder ()
		->select ( 'count(c.id)' )
		->from ( 'CRMSaleBundle:Quote', 'c' )
		->where ( 'c.creationUser = :id' )
		->andWhere ( 'c.quoteStatus = :s' )
		->setParameter ( 'id', $this->getUser ()->getUserName () )
		->setParameter ( 's', 'Won' )
		->getQuery ()->getSingleScalarResult ();
		 
		return $won_deal;
	}
	public function countLoss() {
		$em = $this->getDoctrine ()->getManager ();
		$won_deal = $em->createQueryBuilder ()->select ( 'count(c.id)' )
		->from ( 'CRMSaleBundle:Quote', 'c' )->where ( 'c.creationUser = :id' )
		->andWhere ( 'c.quoteStatus = :s' )
		->setParameter ( 'id', $this->getUser ()
		->getUserName () )
		->setParameter ( 's', 'Loss' )
		->getQuery ()->getSingleScalarResult ();
		
		return $won_deal;
	}
}
