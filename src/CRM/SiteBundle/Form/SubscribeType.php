<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SubscribeType extends AbstractType {

	public function __construct($type) {
		$this->type = $type;
		
	
	}
    /**
     * Builds the AddUser form
     * @param  \Symfony\Component\Form\FormBuilder $builder
     * @param  array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
    	 
        $builder->add('fullname', 'text', array('required' => true));     
        $builder->add('email', 'email', array('required' => true));   
        $builder->add('password', 'password', array('required' => true));
        $builder->add('subscription', 'choice', array(
        		'choices' => array(
        				//'Basic' => 'Basic',
        				//'Professional' => 'Professional',
        				//'Business' => 'Business',
        				//'Enterprise' => 'Enterprise',
        				'Unlimited' => 'Unlimited',
        		), 
        		'multiple' => false,
        		'expanded' => false,  
        		'preferred_choices' => array($this->type)
        ));
        $builder->add('url', 'text', array('required' => true));
    }

    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CRM\ContactBundle\Entity\Subscriber',
            'em' => '',
        );
    }

    public function getName() {
        return 'user';
    }

}
