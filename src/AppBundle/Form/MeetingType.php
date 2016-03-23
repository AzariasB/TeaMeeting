<?php

/*
 * Contains the MeetingType class
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Build a form to create
 * a meeting
 *
 * @author boutina
 */
class MeetingType extends AbstractType {

    /**
     * Build the form
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('date', DateTimeType::class, array(
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text'
                ))
                ->add('room', TextType::class)
                ->add('project', HiddenType::class);
    }

    /**
     * Set the default options
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Meeting'
        ));
    }

}
