<?php

/**
 * Contains the ProjectType class
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * This class build the form to create a project
 *
 * @author boutina
 */
class ProjectType extends AbstractType {

    /**
     * Build the form to create a project.
     * This forms contains :
     * <ul>
     *  <li>The name of the project</li>
     *  <li>The leader</li>
     *  <li>The secretary</li>
     *  <li>The participants</li>
     * </ul>
     * For the roles and the meetings, the leader is able to add
     * the later on when visualising the project
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

    /**
     * Set the defaults options
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Project'
        ));
    }

}
