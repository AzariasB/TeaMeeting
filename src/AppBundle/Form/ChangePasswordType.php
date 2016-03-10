<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of ChangePasswordType
 *
 * @author boutina
 */
class ChangePasswordType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('oldPassword', 'password');
        $builder->add('newPassword', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'The password fields must match.',
            'required' => true,
            'first_options' => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password'),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Form\Model\ChangePassword',
        ));
    }

    public function getName() {
        return 'change_passwd';
    }

}
