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
 * Contains the class UserRequestController
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\UserRequest;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Description of UserRequestController
 *
 * @author boutina
 */
class UserRequestController extends SuperController {

    /**
     * Update the request with the given id
     * 
     * @param type $requestId
     * @param Request $req
     * @return Response
     */
    public function updateRequestAction($requestId, Request $req) {
        $userReq = $this->getEntityFromId(UserRequest::class, $requestId);

        $form = $this->createFormBuilder()
                ->add('state', ChoiceType::class, array(
                    'choices' => array(
                        UserRequest::STATE_AGREED => 'Agreed',
                        UserRequest::STATE_NOTED_ON_AGENDA => 'Noted on agenda',
                        UserRequest::STATE_NOTED_NO_CHANGE => 'Noted, agenda not changed',
                        UserRequest::STATE_PENDING => 'Pending'
                    ),
                    'data' => $userReq->getState()
                ))
                ->add('content', TextareaType::class, array(
                    'data' => $userReq->getContent(),
                    'disabled' => true
                ))
                ->getForm();

        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            return $this->handleUpdateForm($form, $userReq);
        }

        return $this->createUpdateForm($form);
    }

    /**
     * Handle the form to update a request
     * 
     * @param Form $form
     * @param UserRequest $userReq
     * @return type
     */
    private function handleUpdateForm(Form $form, UserRequest $userReq) {
        $resp = new JsonResponse;
        if ($form->isValid()) {
            $nwState = $form->get('state')->getData();
            $userReq->setState($nwState);
            $this->saveEntity($userReq);
            return $resp->setData(array(
                        'success' => true,
                        'request' => $userReq
            ));
        } else {
            return $resp->setData(array(
                        'success' => false,
                        'page' => $this->createUpdateForm($form)
            ));
        }
    }

    private function createUpdateForm(Form $form) {
        return $this->render('meeting/update-request-form.html.twig', array(
                    'form' => $form->createView()
        ));
    }

}
