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
 * Contains the class ItemAgendaController
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Form;
use AppBundle\Form\ItemAgendaType;
use AppBundle\Entity\Agenda;
use AppBundle\Entity\ItemAgenda;
use AppBundle\Entity\Meeting;

/**
 * Controller for an agenda's item
 *
 * @author boutina
 */
class ItemAgendaController extends SuperController {

    /**
     * Update an item of the agenda
     * 
     * @param int $itemId
     * @param Request $req
     * @return Reponse
     */
    public function updateAction($itemId, Request $req) {
        $item = $this->getEntityFromId(ItemAgenda::class, $itemId);

        $arrayMeetings = new ArrayCollection($this->getAllFromClass(Meeting::class));
        $meetings = $arrayMeetings->filter(function($meet) {
                    return !$meet->isOutdated();
                })->toArray();

        $form = $this->createForm(ItemAgendaType::class, $item)
                ->add('meeting', EntityType::class, array(
            'class' => 'AppBundle:Meeting',
            'choices' => $meetings,
            'choice_label' => function(Meeting $meeting) {
                return $meeting->getRoom() . ' - ' . $meeting->getDate()->format('d/M/y');
            }
        ));

        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            return $this->handleUpdateForm($form, $item);
        }

        return $this->createFormPage($form);
    }

    /**
     * Handl the form to update an agenda item
     * 
     * @param Form $form
     * @param ItemAgenda $item
     * @return JsonResponse
     */
    private function handleUpdateForm(Form $form, ItemAgenda $item) {
        $rep = new JsonResponse;
        if ($form->isValid()) {
            $this->saveEntity($item);
            return $rep->setData(array(
                        'success' => true,
                        'item' => $item,
                        'itemMeeting' => $item->getMeeting()
            ));
        } else {
            return $rep->setData(array(
                        'success' => true,
                        'page' => $this->createFormPage($form)
            ));
        }
    }

    /**
     * Remove an item
     * 
     * @param int $itemId
     * @param Request $req
     * @return Reponse
     */
    public function removeAction($itemId, Request $req) {
        $item = $this->getEntityFromId(ItemAgenda::class, $itemId);

        $this->removeEntity($item);

        $rep = new JsonResponse;
        return $rep->setData(array(
                    'success' => true,
                    'removed' => $itemId
        ));
    }

    /**
     * Create the form to create an agenda item
     * 
     * @param int $agendaId
     * @param Request $req
     */
    public function createAction($agendaId, Request $req) {
        $agenda = $this->getEntityFromId(Agenda::class, $agendaId);

        $item = new ItemAgenda;
        $item->setAgenda($agenda);

        $form = $this->createForm(ItemAgendaType::class, $item);

        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            return $this->handleCreateForm($form, $agenda, $item);
        }

        return $this->createFormPage($form);
    }

    /**
     * Handle the form to create an agenda item
     * 
     * @param Form $form
     * @param Agenda $ag
     * @param ItemAgenda $item
     * @return JsonResponse
     */
    private function handleCreateForm(Form $form, Agenda $ag, ItemAgenda $item) {
        $rep = new JsonResponse;
        if ($form->isValid()) {
            $this->saveEntity($item);
            return $rep->setData(array(
                        'success' => true,
                        'item' => $item
            ));
        } else {
            return $rep->setData(array(
                        'success' => false,
                        'page' => $this->createFormPage($form)
            ));
        }
    }

    /**
     * 
     * @param Form $form
     * @return type
     */
    private function createFormPage(Form $form) {
        return $this->render('meeting/item-form.html.twig', array(
                    'form' => $form->createView()
        ));
    }

}
