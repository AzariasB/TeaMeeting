<?php

/*
 * This file contains the MeetingController class
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Form\MeetingType;
use AppBundle\Entity\ItemAgenda;
use AppBundle\Entity\Project;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\UserAnswer;
use AppBundle\Entity\Agenda;

/**
 * Controls the meeting entity.
 * May create one, delete one or edit/change one
 *
 * @author boutina
 */
class MeetingController extends SuperController {

    /**
     * The id of the agenda
     * where the items must be updated
     * 
     * @param int $agendaId
     * @param Request $req
     * @return JsonResponse
     */
    public function saveItemsAction($agendaId, Request $req) {
        $agenda = $this->getEntityFromId(Agenda::class, $agendaId);

        $nwAgendaItems = new ArrayCollection;

        $reqItems = json_decode($req->getContent(), true)['items'];
        foreach ($reqItems as $index => $item) {
            $itemObj = $this->getEntityFromId(ItemAgenda::class, $item['id']);
            $itemObj->setPosition($index);
            $nwAgendaItems->add($itemObj);
        }

        $agenda->setItems($nwAgendaItems);
        $this->saveEntity($agenda);
        $rep = new JsonResponse;
        return $rep->setData(array('success' => true));
    }

    /**
     * Save user answer
     * 
     * @param int $answerId
     * @param Request $req
     * @return Reponse
     * @throws Not found
     */
    public function saveAnswerAction($answerId, Request $req) {

        $rep = new JsonResponse;
        $ans = $this->getEntityFromId(UserAnswer::class, $answerId);

        $answerType = $req->get('answer');
        if ($ans->setAnswer($answerType)) {
            $this->saveEntity($ans);
            return $rep->setData(array(
                        'success' => true,
                        'answer' => $ans
            ));
        } else {
            return $rep->setData(array(
                        'success' => false,
                        'anwser' => $ans,
                        'error' => 'The answer could not be changed'
            ));
        }
    }

    /**
     * Show the meeting with the given Id
     * 
     * @param type $meetingId
     * @param Request $req
     * @return Response
     */
    public function showAction($meetingId, Request $req) {

        $m = $this->getEntityFromId(Meeting::class, $meetingId);
        if (!$m) {
            throw new $this->createNotFoundException('Meeting not found');
        }

        $u = $this->getCurrentUser();

        return $this->render('meeting/meeting.html.twig', array(
                    'meeting' => $m,
                    'canEdit' => $u == $m->getChairman()
        ));
    }

    /**
     * Update an existing meeting
     * 
     * @param int $meetingId
     * @param Request $req
     */
    public function updateAction($meetingId, Request $req) {
        $meeting = $this->getEntityFromId(Meeting::class, $meetingId);
        return $this->createMeetingForm($meeting, $req);
    }

    /**
     * Creates a meeting
     * this function is only callable with xmlhttprequest
     * otherwise it will return not found
     * 
     * @param Request $req
     * @return Response
     */
    public function createAction($projId, Request $req) {
        $p = $this->getEntityFromId(Project::class, $projId);
        $meet = new Meeting();
        $meet->setProject($p);
        return $this->createMeetingForm($meet, $req);
    }

    private function createMeetingForm(Meeting $meet, Request $req) {
        $form = $this->createForm(MeetingType::class, $meet);


        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            return $this->handleForm($form, $meet);
        }

        return $this->renderMeetingForm($form);
    }

    /**
     * Remove the meeting with id 'meetingId'
     * from the database.
     * Once its done return the jsonresponse
     * success : true
     * 
     * @param int $meetingId
     * @param Request $req
     * @return JsonResponse
     * @throws NotFoundHttpException
     */
    public function removeAction($meetingId, Request $req) {

        if ($req->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $meeting = $em->find(Meeting::class, $meetingId);
            $em->remove($meeting);
            $em->flush();
            $rep = new JsonResponse();
            return $rep->setData(array('success' => true));
        }

        throw new NotFoundHttpException("Not found");
    }

    /**
     * Handle the submitted form to create a
     * meeting.
     * Will assign the project to the meeting
     * and return a JsonResponse depending on
     * wether the form is valid
     * 
     * @param Form $form
     * @param Meeting $meet
     */
    private function handleForm(Form $form, Meeting $meet) {

        $rep = new JsonResponse();

        if ($form->isValid()) {
            $ag = new Agenda($meet);
            $this->saveEntity($meet);
            $meet->getProject()->addMeeting($meet);
            $rep->setData(array(
                'success' => true,
                'meeting' => $meet
            ));
        } else {
            $rep->setData(array(
                'success' => false,
                'page' => $this->renderMeetingForm($form)
            ));
        }

        return $rep;
    }

    /**
     * Create the meeting form from
     * the given form
     * 
     * @param Form $form
     * @return Response view
     */
    private function renderMeetingForm(Form $form) {
        return $this->render('project/meetingForm.html.twig', array(
                    'form' => $form->createView()
        ));
    }

}
