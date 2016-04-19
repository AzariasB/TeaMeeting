<?php

/*
 * The MIT License
 *
 * Copyright 2016 boutina.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Contains the class MinuteActionType
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Entity\MinuteAction;

/**
 * Description of MinuteActionType
 *
 * @author boutina
 */
class MinuteActionType extends AbstractType {

    //put your code here
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $action = $options['data'];
        $partcicipants = $action->getItemMinute()->getMeetingMinute()->getMeeting()->getProject()->getParticipants();
        $builder
                ->add('state', ChoiceType::class, array(
                    'choices' => array(
                        'In progress' => MinuteAction::ACTION_IN_PROGRESS,
                        'Late' => MinuteAction::ACTION_LATE,
                        'Completed' => MinuteAction::ACTION_COMPLETE,
                        'No longer required' => MinuteAction::ACTION_NO_LONGER_REQUIRED,
                        'In review' => MinuteAction::ACTION_WORK_UNDER_REVIEW
                    ),
                    'choices_as_values' => true
                ))
                ->add('description', TextareaType::class)
                ->add('implementer', EntityType::class, array(
                    'class' => 'AppBundle:User',
                    'choice_label' => 'username',
                    'choices' => $partcicipants
                ))
                ->add('deadline', DateType::class, array(
                    'widget' => 'single_text',
                    'data' => new \DateTime
        ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\MinuteAction'
        ));
    }

}
