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
 * Contains the class UserPresenceType
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Entity\UserPresence;

/**
 * Description of UserPresenceType
 *
 * @author boutina
 */
class UserPresenceType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('state', ChoiceType::class, array(
            'choices' => array(
                'Always present' => UserPresence::PRESENT_FOR_WHOLE_MEETING,
                'Arrived late' => UserPresence::PRESENT_ARRIVED_LATE,
                'Left early' => UserPresence::PRESENT_LEFT_EARLY,
                'Arrived late and left early' => UserPresence::PRESENT_LEFT_EARLY & UserPresence::PRESENT_ARRIVED_LATE,
                'None' => UserPresence::ABSENT_NO_APOLOGIES,
                'Received after meeting' => UserPresence::ABSENT_APOLOGIES_RECEIVED_AFTER_MEETING,
                'Received before meeting' => UserPresence::ABSENT_APOLOGIES_RECEIVED_BEFORE_MEETING
            ),
            'choices_as_values' => true,
            'group_by' => function($val, $key, $index) {
        if ($val >= UserPresence::ABSENT_NO_APOLOGIES) {
            return 'Absent - apologies';
        } else {
            return 'Present';
        }
    }
        ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\UserPresence'
        ));
    }

}
