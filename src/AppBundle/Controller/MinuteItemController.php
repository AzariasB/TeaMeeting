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
 * Contains the class MinuteItemController
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;
use AppBundle\Entity\ItemMinute;
use AppBundle\Entity\MinuteAction;
use AppBundle\Form\MinuteActionType;

/**
 * This is the controller for a minute item
 *
 * @author boutina
 */
class MinuteItemController extends SuperController {

    /**
     * Returns the minute item with the given id
     * depending on the json boolean the response can be
     * either twig render of json response
     * 
     * @param int $itemId
     * @param boolean $json
     * @return Response
     */
    public function indexAction($itemId, $json = false) {
        $item = $this->getEntityFromId(ItemMinute::class, $itemId);

        $data = ['item' => $item];

        if ($json) {
            return new JsonResponse($data);
        } else {
            $data['canEdit'] = $item
                    ->getMeetingMinute()
                    ->getMeeting()
                    ->userIsChairman($this->getCurrentUser());
            $data['breadcrumbs'] = $this->generateBreadCrumbs($item);
            return $this->render('itemminute/index.html.twig', $data);
        }
    }

    /**
     * Toggle the postponed variable of the
     * item with the given id
     * 
     * @param type $itemId
     * @param Request $req
     * @return JsonResponse;
     */
    public function toggleAction($itemId, Request $req) {
        $item = $this->getEntityFromId(ItemMinute::class, $itemId);
        $item->togglePostponed();
        $this->saveEntity($item);
        return new JsonResponse(['success' => true, 'item' => $item]);
    }

    /**
     * Funny name right ?
     * 
     * Add an action to the minute item
     * 
     * @param type $itemId
     * @param Request $req
     * @return JsonResponse;
     */
    public function addActionAction($itemId, Request $req) {
        $item = $this->getEntityFromId(ItemMinute::class, $itemId);

        $action = new MinuteAction();

        $action->setItemMinute($item);

        return $this->createActionForm($action, $req);
    }

    /**
     * Edit the action of the given id,
     * the action of the given item
     * 
     * @param int $itemId
     * @param int $actionId
     * @param Request $req
     * @return Response
     */
    public function editActionAction($itemId, $actionId, Request $req) {
        $action = $this->getEntityFromId(MinuteAction::class, $actionId);
        return $this->createActionForm($action, $req);
    }

    /**
     * Called whenever a user submit an action
     * to the chairman
     * This will change the state of the action to
     * 'work under review'
     * 
     * @param type $itemId
     * @param type $actionId
     * @param Request $req
     * @return type
     */
    public function submitActionAction($itemId, $actionId, Request $req) {
        $action = $this->getEntityFromId(MinuteAction::class, $actionId);
        $action->setState(MinuteAction::ACTION_WORK_UNDER_REVIEW);
        $this->saveEntity($action);
        return new JsonResponse(['success' => true, 'action' => $action]);
    }

    /**
     * Creates the form to create an action
     * 
     * @param MinuteAction $action
     * @param Request $req
     * @return Response
     */
    private function createActionForm(MinuteAction $action, Request $req) {
        $form = $this->createForm(MinuteActionType::class, $action);

        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            return $this->handleCreationForm($form, $action);
        }

        return $this->renderFormView($form);
    }

    /**
     * Handl the form to create an action
     * 
     * @param Form $form
     * @param MinuteAction $action
     * @return JsonResponse
     */
    private function handleCreationForm(Form $form, MinuteAction $action) {
        $rep = new JsonResponse;
        if ($form->isValid()) {
            $this->saveEntity($action);
            return $rep->setData(array(
                        'success' => true,
                        'action' => $action
            ));
        } else {
            return $rep->setData(array(
                        'success' => false,
                        'page' => $this->renderFormView($form)
            ));
        }
    }

    private function renderFormView(Form $form) {
        return $this->render('itemminute/actionForm.html.twig', array(
                    'form' => $form->createView()
        ));
    }

}
