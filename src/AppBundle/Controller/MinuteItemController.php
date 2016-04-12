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
 * Description of MinuteItemController
 *
 * @author boutina
 */
class MinuteItemController extends SuperController {

    public function indexAction($itemId, $json = false) {
        $item = $this->getEntityFromId(ItemMinute::class, $itemId);

        $data = ['item' => $item];

        if ($json) {
            $rep = new JsonResponse;
            return $rep->setData($data);
        } else {
            $data['canEdit'] = $item
                    ->getMeetingMinute()
                    ->getMeeting()
                    ->userIsChairman($this->getCurrentUser());
            $data['breadcrumbs'] = $this->generateBreadCrumbs($item);
            return $this->render('itemminute/index.html.twig', $data);
        }
    }

    public function toggleAction($itemId, Request $req) {
        $item = $this->getEntityFromId(ItemMinute::class, $itemId);
        $item->togglePostponed();
        $this->saveEntity($item);
        $rep = new JsonResponse;
        return $rep->setData(array(
                    'success' => true,
                    'item' => $item
        ));
    }

    public function addActionAction($itemId, Request $req) {
        $item = $this->getEntityFromId(ItemMinute::class, $itemId);

        $action = new MinuteAction();

        $action->setItemMinute($item);

        return $this->createActionForm($action, $req);
    }

    public function editActionAction($itemId, $actionId, Request $req) {
        $action = $this->getEntityFromId(MinuteAction::class, $actionId);
        return $this->createActionForm($action, $req);
    }

    public function submitActionAction($itemId, $actionId, Request $req) {
        $action = $this->getEntityFromId(MinuteAction::class, $actionId);
        $action->setState(MinuteAction::ACTION_WORK_UNDER_REVIEW);
        $this->saveEntity($action);
        $rep = new JsonResponse;
        return $rep->setData(array(
                    'success' => true,
                    'action' => $action
        ));
    }

    private function createActionForm(MinuteAction $action, Request $req) {
        $form = $this->createForm(MinuteActionType::class, $action);

        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            return $this->handleForm($form, $action);
        }

        return $this->renderFormView($form);
    }

    private function handleForm(Form $form, MinuteAction $action) {
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
