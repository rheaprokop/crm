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
use CRM\SaleBundle\Entity\ProductCategory;
use CRM\SaleBundle\Entity\Product;
use CRM\SaleBundle\Entity\ProductActivity;
use CRM\SaleBundle\Form\ProductType;
use CRM\SaleBundle\Form\ProductCatType;
use CRM\SaleBundle\Form\ProductActivityType;

class ProductCatController extends Controller {

    public function manageAction() {
        $this->getTheme();
        $em = $this->getDoctrine()->getManager();

        //creates record of category 
        //
        $category = new ProductCategory();
        $form = $this->get('form.factory')->create(new ProductCatType(), $category);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();
                $category->setCreationDate(new \DateTime());
                $category->setCreationUser($this->getUser()->getUsername());
                $em->persist($category);
                $em->flush();

//This page will redirect to View Profile.
                return $this->redirect($this->generateUrl('CRMSaleBundle_product_category_manage'));
            }
        }

        ///////// displays all categories
        $count = $em->createQueryBuilder()
                        ->select('count(category.id)')
                        ->from('CRMSaleBundle:ProductCategory', 'category')
                        ->where('category.creationUser = :u')
                        ->setParameter('u', $this->getUser()->getUsername())
                        ->getQuery()->getSingleScalarResult();


        //Populate list of groups in Manage contact page
        $categories = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSaleBundle:ProductCategory', 'b')
                ->where('b.creationUser = :u')
                ->setParameter('u', $this->getUser()->getUsername())
                ->getQuery()
                ->getResult();


        return $this->render('CRMSaleBundle:ProductCat:manage.html.twig', array(
                    'categories' => $categories,
                    'count' => $count,
                    'form' => $form->createView(),
        ));
    }

    /**
     * This forms display the data and will call updateAction to continually update the provided $id
     *
     * @Route("/{id}/edit", name="CRMSaleBundle_product_category_edit")
     * @Template()
     */
    public function editAction($id) {
        $this->getTheme();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CRMSaleBundle:ProductCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $edit_form = $this->createForm(new ProductCatType(), $entity);

        return array(
            'entity' => $entity,
            'edit_form' => $edit_form->createView(),
        );
    }

    /**
     * Edits an existing Category entity.
     *
     * @Route("/{id}/update", name="CRMSaleBundle_product_category_edit")
     * @Method("post")
     * @Template("CRMSaleBundle:ProductCat:edit.html.twig")
     */
    public function updateAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CRMSaleBundle:ProductCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $edit_form = $this->createForm(new ProductCatType(), $entity);

        $request = $this->getRequest();

        $edit_form->bind($request);

        if ($edit_form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('CRMSaleBundle_product_category_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $edit_form->createView(),
        );
    }

    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $count = $em->createQueryBuilder()
                        ->select('count(product.id)')
                        ->from('CRMSaleBundle:ProductCatList', 'product')
                        ->where('product.category = :cat')
                        ->setParameter('cat', $id)
                        ->getQuery()->getSingleScalarResult();

        if ($count >= 1) {
            $this->get('session')->getFlashBag()->add('notice_product_exists', 'error');
        } else {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CRMSaleBundle:ProductCategory')->find($id);

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice_product_cat_delete', 'success');
        }
        return $this->redirect($this->generateUrl('CRMSaleBundle_product_category_manage'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm();
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
