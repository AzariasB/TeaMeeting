<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Description of RoleType
 *
 * @author boutina
 */
class RoleType extends AbstractType {


    public function buildForm(FormBuilderInterface $builder, array $options) {
        $proj = $options['project'];
        $choices = [];
        if($proj){
          $choices = $proj->getParticipants();
        }
        $builder
                ->add('student', EntityType::class, array(
                    'class' => 'AppBundle:User',
                    'choice_label' => 'username',
                    'choices' => $choices
                    ))
                ->add('roleName', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver) {
       $resolver->setDefaults(array(
           'data_class' => 'AppBundle\Entity\UserRole',
           'project' => null
       ));
    }

}
