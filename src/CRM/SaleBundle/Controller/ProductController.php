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
use CRM\SaleBundle\Entity\Product;
use CRM\SaleBundle\Entity\ProductActivity;
use CRM\SaleBundle\Form\ProductType;
use CRM\SaleBundle\Form\ProductActivityType;
use CRM\SaleBundle\Entity\PayTerm;
use CRM\SaleBundle\Form\PayTermType;

class ProductController extends Controller {

    public function manageAction() {
        $this->getTheme();
        $em = $this->getDoctrine()->getManager();

        $count = $em->createQueryBuilder()
                        ->select('count(product.id)')
                        ->from('CRMSaleBundle:Product', 'product')
                        ->where('product.creationUser = :u')
                        ->setParameter('u', $this->getUser()->getUsername())
                        ->getQuery()->getSingleScalarResult();


//Populate list of groups in Manage contact page
        $categories = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:ProductCategory', 'b')
                ->getQuery()
                ->getResult();

        $products = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:Product', 'b')
                ->where('b.creationUser = :u')
                ->setParameter('u', $this->getUser()->getUsername())
                ->addOrderBy('b.productname', 'ASC')
                ->getQuery()
                ->getResult();

//Populate recent contact for widget 
        $offset = 0;
        $limit = 1;
        $products_recent = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:Product', 'b')
                ->where('b.creationUser = :u')
                ->setParameter('u', $this->getUser()->getUsername())
                ->addOrderBy('b.dateAdded', 'DESC')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();


        return $this->render('CRMSaleBundle:Product:manage.html.twig', array(
                    'products' => $products,
                    'currency' => $this->getCurrency(),
                    'products_recent' => $products_recent,
                    'categories' => $categories,
                    'count' => $count
        ));
    }

    public function createAction() {
        $this->getTheme();
        $em = $this->get('doctrine')->getManager();

        $product = new Product();
        $form = $this->get('form.factory')->create(new ProductType($this->getUser()->getUsername()), $product);

        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {

            $form->bind($request);
            if ($form->isValid()) {

                $em = $this->get('doctrine')->getManager();
                $product->setCreationUser($this->getUser()->getUsername());
                $product->setDateAdded(new \DateTime());
//                $paytype = $em->getRepository('CRMSaleBundle:PayTerm')->find($product->getPricetype());
//                $product->setPricetype($paytype->getId());

                $em->persist($product);
                $em->flush();

                $this->get('session')->getFlashBag()->set('notice', 'You have successfully added '
                        . $product->getProductName() . ' to the database!');

                $this->getProductActivity("creation_user created new product: " . $product->getProductName(), $product->getId());

//This page will redirect to View Profile.  
                return $this->redirect($this->generateUrl('CRMSaleBundle_product_view', array('id' => $product->getId())));
            }
        }

//Populate recent contact for widget 
        $offset = 0;
        $limit = 10;
        $recent_products = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:Product', 'b')
                ->addOrderBy('b.dateAdded', 'DESC')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();

        $units = $em->createQueryBuilder()
        ->select('b')
        ->from('CRMSaleBundle:PayTerm', 'b')
        ->where('b.creationUser = :user')
        ->setParameter('user', $this->getUser()->getUsername()) 
        ->getQuery()
        ->getResult();
        
        return $this->render('CRMSaleBundle:Product:create.html.twig', array(
                    'entity' => $product, 'recent_products' => $recent_products, 'units' => $units,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Deletes a Contact entity.
     *
     * @Route("/{id}/delete", name="CRMSaleBundle_product_delete")
     * @Method("post")
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $delete_form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $delete_form->bind($request);

        if ($delete_form->isValid()) {

            $entity = $em->getRepository('CRMSaleBundle:Product')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Order entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notitce_delete_success', 'success');
        }

        return $this->redirect($this->generateUrl('CRMSaleBundle_product_manage'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    /**
     * Finds and displays a Contact entity.
     *
     * @Route("/{id}/show", name="CRMSaleBundle_product_view")
     * @Template()
     */
    public function productAction($id) {
        $this->getTheme();
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('CRMSaleBundle:Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }
        $type = $this->getDoctrine()->getRepository('CRMSaleBundle:ProductCatList');
        $product_types = $type->createQueryBuilder('a')
                ->select('u.name')
                ->join('a.category', 'u')
                ->where('a.product = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();

        $em = $this->getDoctrine()->getManager();
//Populate recent contact for widget 
        $offset = 0;
        $limit = 15;
        $recent_products = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:Product', 'b')
                ->where('b.creationUser = :u')
                ->setParameter('u', $this->getUser()->getUsername())
                ->addOrderBy('b.dateAdded', 'DESC')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();

        $deleteForm = $this->createDeleteForm($id);

        $offset = 0;
        $limit = 10;
        $recent_activities = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:ProductActivity', 'b')
                ->addOrderBy('b.dateAdded', 'DESC')
                ->where('b.productId = :id')
                ->setParameter('id', $id)
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();

        return $this->render('CRMSaleBundle:Product:product.html.twig', array('id' => $id,
                    'product' => $product,
                    'product_types' => $product_types,
                    'recent_products' => $recent_products,
                    'recent_activities' => $recent_activities,
                    'delete_form' => $deleteForm->createView()));
    }

    /**
     * This forms display the data and will call updateAction to continually update the provided $id
     *
     * @Route("/{id}/edit", name="CRMSaleBundle_product_edit")
     * @Template()
     */
    public function editAction($id) {
        $this->getTheme();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CRMSaleBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }

        $em = $this->getDoctrine()->getManager();
//Populate recent contact for widget 
        $offset = 0;
        $limit = 5;
        $recent_products = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:Product', 'b')
                ->addOrderBy('b.dateAdded', 'DESC')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();

        $edit_form = $this->createForm(new ProductType($this->getUser()->getUsername()), $entity);

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $edit_form->createView(),
            'delete_form' => $deleteForm->createView(),
            'recent_products' => $recent_products
        );
    }

    /**
     * Edits an existing Contact entity.
     *
     * @Route("/{id}/update", name="CRMSaleBundle_product_edit")
     * @Method("post")
     * @Template("CRMSaleBundle:Product:edit.html.twig")
     */
    public function updateAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CRMSaleBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $edit_form = $this->createForm(new ProductType($this->getUser()->getUsername()), $entity);

        $previousCollections = $entity->getCatList();
        $previousCollections = $previousCollections->toArray();

        $request = $this->getRequest();

        $edit_form->bind($request);

        foreach ($previousCollections as $catlist) {
            $entity->removeCatList($catlist);
            $em->remove($catlist);
        }

        if ($edit_form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->getProductActivity("creation_user updated product: " . $entity->getProductName(), $id);

            return $this->redirect($this->generateUrl('CRMSaleBundle_product_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $edit_form->createView(),
        );
    }

    public function getProductActivity($activiydesc, $id) {

        $activity = new ProductActivity();
        $form1 = $this->get('form.factory')->create(new ProductActivityType(), $activity);
        $request1 = $this->get('request');

        if ($request1->getMethod() == 'POST') {
            $form1->bind($request1);
            $activity->setActivityDesc($activiydesc);
            $activity->setProductId($id);
            $activity->setDateAdded(new \DateTime());
            $activity->setActivityUser($this->getUser()->getUsername());
            $em1 = $this->get('doctrine')->getManager();
            $em1->persist($activity);
            $em1->flush();
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

}
