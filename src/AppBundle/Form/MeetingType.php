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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * Description of MeetingType
 *
 * @author boutina
 */
class MeetingType extends AbstractType {

    //put your code here
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $today = new \DateTime();
        $actualYear = intval($today->format('Y'));

        $builder->add('date',  DateTimeType::class,array(
            'years' => range($actualYear-2, $actualYear+2)
        ))
                ->add('room', TextType::class);
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Meeting'
        ));
    }

}
