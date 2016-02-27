<?php

namespace CRM\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContext;
use CRM\ContactBundle\Entity\Category;
use CRM\UserBundle\Entity\User;
use CRM\UserBundle\Entity\UserActivity; 
use CRM\UserBundle\Form\UserActivityType;
use CRM\SaleBundle\Entity\ShippingMethod;
use CRM\SaleBundle\Entity\PayTerm;
use CRM\SiteBundle\Form\TrialType;
use CRM\SiteBundle\Form\SubscribeType;

class SiteController extends Controller
{
    public function indexAction()
    {
    	
    	$user = new User();
        $form = $this->get('form.factory')->create(new TrialType(), $user);
        $request = $this->get('request');
        $form->bind($request);
        
        if ($request->getMethod() == 'POST') {  
            
            if ($form->isValid()) {
            	 
            	$em = $this->get('doctrine')->getManager();
            	//count if email already exists
            	$u = $em->createQueryBuilder()
            	->select('count(c.id)')
            	->from('CRMUserBundle:User', 'c')
            	->where('c.email = :u')
            	->setParameter('u', $user->getEmail())
            	->getQuery()->getSingleScalarResult();
            	
            	if($u >= 1)
            	{
            		$this->get('session')->getFlashBag()->set('notice_duplicate', 'duplicate');
            		return $this->redirect($this->generateUrl('CRMSiteBundle_homepage'));
            	}
            	$datetime = new \DateTime("now");
            	$d = $datetime->format('ymdh');
                
                $user->setCreationDate(new \DateTime());
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);
                $user->setUsername($user->getEmail());
                $user->setEnabled('1');
                $user->setExpiresAt(date_modify($datetime, '+10 days'));
                $user->setCreationUser($user->getEmail());
                
                $em->persist($user);
                $em->flush();

                $this->createInitialData($user->getEmail());

                $this->get('session')->getFlashBag()->set('notice_activation', 'activation');

                $this->getUserActivity("creation_user created new user: " . $user->getFullName() . " (" . $user->getUsername() . ")", $user->getUsername());
            
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.context')->setToken($token);
                $this->get('session')->set('_security_main',serialize($token));
                //This page will redirect to View Profile.    
                
                $this->mailTrialAction($user->getEmail()); 
                
                return $this->redirect($this->generateUrl('CRMUserBundle_edit_workspace', array('id' => $user->getId())));
                
               
                
            }
        }

        return $this->render('CRMSiteBundle:Site:index.html.twig', array(
                    'entity' => $user,
                    'form' => $form->createView()
        ));
    }
    
    public function subscribeAction($type)
    {
    	
    	$em = $this->get('doctrine')->getManager();
    	$user = new User();
    	$form = $this->get('form.factory')->create(new SubscribeType($type), $user);
    	$request = $this->get('request');
    	$form->bind($request);
    	if ($request->getMethod() == 'POST') {
    		 
    		//count if email already exists
    		$u = $em->createQueryBuilder()
    		->select('count(c.id)')
    		->from('CRMUserBundle:User', 'c')
    		->where('c.email = :u')
    		->setParameter('u', $user->getEmail())
    		->getQuery()->getSingleScalarResult();
    	
    		if($u >= 1)
    		{
    			$this->get('session')->getFlashBag()->set('notice_duplicate', 'duplicate');
    			return $this->redirect($this->generateUrl('CRMSiteBundle_subscribe', array(
                   			'type' => $user->getSubscription(),
       			 )));
    		}
    		 
    	
    		if ($form->isValid()) {
    	
    	
    			$user->setCreationDate(new \DateTime());
    			$factory = $this->get('security.encoder_factory');
    			$encoder = $factory->getEncoder($user);
    			$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
    			$user->setPassword($password);
    			$user->setUsername($user->getEmail());
    			$user->setEnabled('0');
    			//$user->setExpiresAt(new \DateTime());
    			$user->setCreationUser($user->getEmail());
    			$em->persist($user);
    			$em->flush();
    	
    			$this->createInitialData($user->getEmail());
    	
    			$this->get('session')->getFlashBag()->set('notice_activation', 'activation');
    	
    			$this->getUserActivity("creation_user created new user: " . $user->getFullName() . " (" . $user->getUsername() . ")", $user->getUsername());
    	
    			 
    			//This page will redirect to View Profile.
    			return $this->redirect($this->generateUrl('CRMSiteBundle_process_payment', array(
    						'user' => $user, 'id' => $user->getId() 
       			 )));
    		}
    	}
    	return $this->render('CRMSiteBundle:Site:subscribe.html.twig', array(
                    'entity' => $user,
                    'form' => $form->createView(), 'type' => $type
        ));
    }

    public function processpayAction($id)
    { 
    	$em = $this->getDoctrine()->getManager();
    	
    	$entity = $em->getRepository('CRMUserBundle:User')->find($id);
    	
    	return $this->render('CRMSiteBundle:Site:processpay.html.twig', array(
                    'entity' => $entity
        ));
    }
    
    public function mailTrialAction($email) {  
    
    	$message = \Swift_Message::newInstance()
    
    	// Give the message a subject
    	->setSubject('CandySW: Thank you for signing up.')
    
    	// Set the From address with an associative array
    	->setFrom('crm@candysw.com')
    
    	// Set the To addresses with an associative array
    	->setTo($email)
    	
    	->setContentType("text/html")
    
    	// Give it a body
    	->setBody(
            $this->renderView(
                'CRMSiteBundle:Site:confirmation.html.twig')
        )
    ;
    
    	$this->get('mailer')->send($message);  
    }
    
    public function getUserActivity($activiydesc, $email) {
    
    	$activity = new UserActivity();
    	$form1 = $this->get('form.factory')->create(new UserActivityType(), $activity);
    	$request1 = $this->get('request');
    
    	if ($request1->getMethod() == 'POST') {
    		$form1->bind($request1);
    		$activity->setActivityDesc($activiydesc);
    		$activity->setDateAdded(new \DateTime());
    		$activity->setActivityUser($email);
    		$activity->setModule('User');
    		$em1 = $this->get('doctrine')->getManager();
    		$em1->persist($activity);
    		$em1->flush();
    	}
    }
    
    public function createInitialData($email) {
    	$em = $this->get('doctrine')->getManager();
    
    	///When user information has been added, the initial data are consequently added.
    	//1. Contact Categories: (Lead & Opportunity):
    	$contact_cat = new Category();
    	$contact_cat->setName("Lead");
    	$contact_cat->setCreationDate(new \DateTime());
    	$contact_cat->setCreationUser($email);
    	$em->persist($contact_cat);
    	$em->flush();
    
    	$contact_cat1 = new Category();
    	$contact_cat1->setName("Opportunity");
    	$contact_cat1->setCreationDate(new \DateTime());
    	$contact_cat1->setCreationUser($email);
    	$em->persist($contact_cat1);
    	$em->flush();
    
    	$shipping_method = new ShippingMethod();
    	$shipping_method->setName('Free');
    	$shipping_method->setAmount('0');
    	$shipping_method->setCreationDate(new \DateTime());
    	$shipping_method->setCreationUser($email);
    	$em->persist($shipping_method);
    	$em->flush();
    
    	$payterm = new PayTerm();
    	$payterm->setName('Piece');
    	$payterm->setAbbrev('pc');
    	$payterm->setCreationDate(new \DateTime());
    	$payterm->setCreationUser($email);
    	$em->persist($payterm);
    	$em->flush();
    
    	$payterm1 = new PayTerm();
    	$payterm1->setName('Box');
    	$payterm1->setAbbrev('box');
    	$payterm1->setCreationDate(new \DateTime());
    	$payterm1->setCreationUser($email);
    	$em->persist($payterm1);
    	$em->flush();
    
    	$payterm2 = new PayTerm();
    	$payterm2->setName('Pallet');
    	$payterm2->setAbbrev('Pal');
    	$payterm2->setCreationDate(new \DateTime());
    	$payterm2->setCreationUser($email);
    	$em->persist($payterm2);
    	$em->flush();
    }

	public function confirmationAction()
	{
		return $this->render('CRMSiteBundle:Site:confirmation.html.twig');
	}
}
