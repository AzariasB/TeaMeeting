<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Description of ProjectType
 *
 * @author boutina
 */
class ProjectType extends AbstractType {

    /**
     * @todo Add an option to create custom roles
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('projectName', TextType::class)
                ->add('leader', EntityType::class, array(
                    'class' => 'AppBundle:User',
                    'choice_label' => 'username'))
                ->add('secretary', EntityType::class, array(
                    'class' => 'AppBundle:User',
                    'choice_label' => 'username'))
                ->add('participants', EntityType::class, array(
                    'class' => 'AppBundle:User',
                    'choice_label' => 'username',
                    'multiple' => true,
                    'expanded' => true
                    )
        );
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Project'
        ));
    }

}
