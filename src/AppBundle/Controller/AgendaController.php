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
 * Contains the controller AgendaController
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;
use AppBundle\Form\UserRequestType;
use AppBundle\Entity\Agenda;
use AppBundle\Entity\UserRequest;

/**
 * Controlls the access to the agenda entity
 *
 * @author boutina
 */
class AgendaController extends SuperController {

    public function sendRequestAction($agendaId, Request $req) {
        $agenda = $this->getEntityFromId(Agenda::class, $agendaId);

        if (!$agenda) {
            throw $this->createNotFoundException('Agenda not found');
        }

        $userReq = new UserRequest;
        $userReq->setAgenda($agenda);
        $userReq->setSender($this->getCurrentUser());

        $form = $this->createForm(UserRequestType::class, $userReq);

        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            return $this->handleForm($form, $userReq);
        }

        return $this->sendRequestView($form);
    }

    private function handleForm(Form $form, UserRequest $userReq) {
        $rep = new JsonResponse;
        if ($form->isValid()) {
            $this->saveEntity($userReq);
            return $rep->setData(array(
                        'success' => true,
                        'request' => $userReq
            ));
        } else {
            return $rep->setData(array(
                        'success' => false,
                        'page' => $this->sendRequestView($form)
            ));
        }
    }

    /**
     * Return the agenda in json format
     * 
     * @param int $agendaId
     * @param Request $req
     * @return JsonReponse
     */
    public function getAgendaAction($agendaId, Request $req) {
        $rep = new JsonResponse;
        $agenda = $this->getEntityFromId(Agenda::class, $agendaId);
        $agenda->getItems();
        $agenda->getRequests();
        return $rep->setData($agenda);
    }


    /**
     * Create the view to create a request
     * 
     * @param Form $form
     * @return Response
     */
    private function sendRequestView(Form $form) {
        return $this->render('meeting/request-form.html.twig', array(
                    'form' => $form->createView()
        ));
    }

}
