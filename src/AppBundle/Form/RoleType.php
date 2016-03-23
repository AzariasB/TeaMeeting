<?php

/*
 * Contains the class RoleType
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

    /**
     * Build the form to create a role
     * Contains :
     * <ul>
     *  <li>One student</li>
     *  <li>The name of the role</li>
     * </ul>
     * The particularity of this form, is that the choices
     * of the user is limited to the participants of the project
     * and does not contains all the users of the database
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $proj = $options['data']->getProject();
        if ($proj) {
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

    /**
     * Set the default options
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\UserRole'
        ));
    }

}
