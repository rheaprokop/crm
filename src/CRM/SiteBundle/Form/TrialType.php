<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TrialType extends AbstractType {

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
    }

    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CRM\ContactBundle\Entity\User',
            'em' => '',
        );
    }

    public function getName() {
        return 'user';
    }

}
