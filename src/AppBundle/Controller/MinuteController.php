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
 * Contains the class MinuteController
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\MeetingMinute;
use AppBundle\Entity\Meeting;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Form\MeetingMinuteType;
use AppBundle\Entity\UserPresence;
use AppBundle\Form\UserPresenceType;

/**
 * Description of MinuteController
 *
 * @author boutina
 */
class MinuteController extends SuperController {

    /**
     * Show the meeting minute of the meeting
     * (available only when the meeting is finished)
     * 
     * @param int $meetingId
     * @param Request $req
     * @return Response
     */
    public function indexAction($meetingId, Request $req) {
        $meeting = $this->getEntityFromId(Meeting::class, $meetingId);

        if (!$meeting->isOutdated()) {
            throw $this->createNotFoundException('The meeting is not finished yet');
        }

        $minute = $meeting->getCurrentMinute();

        if (!$minute) {
            $minute = new MeetingMinute();
            $minute->setMeeting($meeting);
            $meeting->addMinute($minute);
            $this->saveEntity($meeting);
        }

        return $this->render('minute/minute.html.twig', [
                    'minute' => $minute,
                    'canEdit' => $meeting->userIsSecretary($this->getCurrentUser())
        ]);
    }

    public function minuteJsonAction($meetingId, Request $req) {
        $meeting = $this->getEntityFromId(Meeting::class, $meetingId);

        $minute = $meeting->getCurrentMinute();

        $rep = new JsonResponse;
        return $rep->setData(array(
                    'minute' => $minute
        ));
    }
    
    public function editPresenceAction($presenceId,Request $req){
        $presence = $this->getEntityFromId(UserPresence::class, $presenceId);
        
        $form = $this->createForm(UserPresenceType::class,$presence);
        
        $form->handleRequest($req);
        
        if($form->isSubmitted()){
            return $this->handleEditPresenceForm($presence, $form);
        }
        
        return $this->renderPresenceFormView($form);
    }
    
    private function handleEditPresenceForm(UserPresence $pres,Form $form){
        $res = new JsonResponse;
        if($form->isValid()){
            $this->saveEntity($pres);
            return $res->setData(array(
                'success' => true,
                'presence' => $pres
            ));
        }else{
            return $res->setData(array(
                'success' => false,
                'page' => $this->renderPresenceFormView($form)
            ));
        }
    }
    
    private function renderPresenceFormView(Form $form){
        return $this->render('minute/presence.html.twig',array(
            'form' => $form->createView()
        ));
    }

}
