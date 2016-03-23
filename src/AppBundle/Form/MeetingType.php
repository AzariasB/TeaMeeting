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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Project;

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
        $prot = $this->getProject($options);

        $choices = [];
        $leader = null;
        $secr = null;
        if ($prot) {
            $choices = $prot->getParticipants();
            $leader = $prot->getLeader();
            $secr = $prot->getSecretary();
        }

        $builder->add('date', DateTimeType::class, array(
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    'data' => new \DateTime
                ))
                ->add('room', TextType::class)
                ->add('chairMan', EntityType::class, array(
                    'choice_label' => 'username',
                    'class' => 'AppBundle:User',
                    'choices' => $choices,
                    'data' => $leader
                ))
                ->add('secretary', EntityType::class, array(
                    'choice_label' => 'username',
                    'class' => 'AppBundle:User',
                    'choices' => $choices,
                    'data' => $secr
        ));
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

    /**
     * Find the project in the options array
     * return null if not found
     * 
     * @param array $options
     * @return Project
     */
    private function getProject(array $options) {
        $data = $options['data'];
        return $data ? $data->getProject() : null;
    }

}
