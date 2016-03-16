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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Entity\Project;

/**
 * Description of ProjectRolesType
 *
 * @author boutina
 */
class ProjectRolesType extends AbstractType {

    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('roles', CollectionType::class, array(
                    'entry_type' => RoleType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_options' => array(
                        'project' => $options['data']
                    )
                ))
                ->add('meetings', CollectionType::class, array(
                    'entry_type' => MeetingType::class,
                    'allow_add' => true,
                    'allow_delete' => true
        ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Project'
        ));
    }

}
