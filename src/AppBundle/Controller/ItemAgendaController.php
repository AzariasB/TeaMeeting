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

use AppBundle\Entity\Agenda;
use AppBundle\Entity\ItemAgenda;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ItemAgendaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;

/**
 * Description of ItemAgendaController
 *
 * @author boutina
 */
class ItemAgendaController extends SuperController {

    /**
     * Create the form to create an agenda item
     * 
     * @param int $agendaId
     * @param Request $req
     */
    public function createAction($agendaId, Request $req) {
        $agenda = $this->getEntityFromId(Agenda::class, $agendaId);

        if (!$agenda) {
            throw $this->createNotFoundException('Agenda not found');
        }

        $item = new ItemAgenda;

        $form = $this->createForm(ItemAgendaType::class, $item,array(
            'agenda' => $agenda
        ));

        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            return $this->handleForm($form, $agenda, $item);
        }

        return $this->createFormPage($form);
    }

    private function handleForm(Form $form, Agenda $ag, ItemAgenda $item) {
        $rep = new JsonResponse;
        if ($form->isValid()) {
            $item->setAgenda($ag);
            $this->saveEntity($item);
            $this->saveEntity($ag);
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
