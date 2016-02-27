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

/**
 * Creates a new Contact entity.
 *
 * @Route("/create", name="CRMContactBundle_create_account")
 * @Method("post")
 * @Template("CRMContactBundle:Account:create.html.twig")
 */
class LeadController extends Controller {

    public function indexAction() {
        $this->getTheme(); 
        
            $em = $this->getDoctrine()->getManager();
            $categories = $em->createQueryBuilder()
                    ->select('b')
                    ->from('CRMContactBundle:Category', 'b')
                    ->where('b.creationUser = :id')
                    ->setParameter('id', $this->getUser()->getUsername())
                    ->getQuery()
                    ->getResult();
            
            $query = $em->createQuery(
                    'SELECT a
                        FROM  CRMContactBundle:Contact a
                        INNER JOIN  CRMContactBundle:ContactCatList b
                        WHERE b.contact = a.id
                        AND b.category = (Select c.id from CRMContactBundle:Category c where c.name = \'Lead\' and c.creationUser = \'' . $this->getUser()->getUsername() . '\')
                        ORDER BY a.firstname ASC'
            );
            
            $leads = $query->getResult();

            $offset = 0;
            $limit = 5;
            $contacts_recent = $em->createQueryBuilder()
                    ->select('b')
                    ->from('CRMContactBundle:Contact', 'b') 
                    ->where('b.creation_user = :u')
                    ->setParameter('u', $this->getUser()->getUsername())
                    ->addOrderBy('b.dateAdded', 'DESC')
                    ->setFirstResult($offset)
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
         

        return $this->render('CRMSaleBundle:Lead:index.html.twig', array(
                    'leads' => $leads,
                    'contacts_recent' => $contacts_recent,
                    'categories' => $categories
        ));
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
