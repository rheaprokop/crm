<?php

/*
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */

namespace CRM\SaleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SaleOrderStatusType extends AbstractType {

    public function __construct($status) { 
        $this->status = $status;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        switch ($this->status) {
                case 'Prepared':
                    $status = "4f8ebbe84c8";
                    break;
                case 'Delivered':
                    $status = "5706de961fb";
                    break;
                case 'Unclear':
                    $status = "19abd416eb9";
                    break;
                case 'Cleared':
                    $status = "14781ee5e85";
                    break;
                case 'Draft':
                    $status = "26581ee5df4";
                    break;
                default:
                	$status = "26581ee5df4";
                    break;
            }
            
        $builder->add('orderStatus', 'choice', array(
            'choices' => array(
                '4f8ebbe84c8' => 'Prepared',
                '5706de961fb' => 'Delivered',
                '19abd416eb9' => 'Not Clear', 
                '14781ee5e85' => 'Cleared', 
                '26581ee5df4' => 'Draft'
            ),
            'required' => true,
            'data' => $status,
        ));
    }

    /**
     * Returns the default options/class for this form.
     * @param array $options
     * @return array The default options
     */
    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => '',
            'em' => '',
        );
    }

    /**
     * Mandatory in Symfony2
     * Gets the unique name of this form.
     * @return string
     */
    public function getName() {
        return 'status';
    }

}
