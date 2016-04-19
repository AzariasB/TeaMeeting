<?php

/*
 * Contains the class UserType
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Build the form to create a user
 *
 * @author boutina
 */
class UserType extends AbstractType {
    
    /**
     * Build the form
     * Contains only the username.
     * Since only the admin can create a user, the password
     * is not set by him, but is randomly generated
     * and sent back to the admin
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('username',  TextType::class);
    }
    
    /**
     * Set the default options
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }
}
