<?php

/*
 * This file contains the MeetingController class
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;
use AppBundle\Form\MeetingType;
use AppBundle\Entity\Project;
use AppBundle\Entity\Meeting;

/**
 * Controls the meeting entity.
 * May create one, delete one or edit/change one
 *
 * @author boutina
 */
class MeetingController extends Controller {

    /**
     * Creates a meeting
     * this function is only callable with xmlhttprequest
     * otherwise it will return not found
     * 
     * @param Request $req
     * @return Response
     */
    public function createAction(Request $req) {

        // Display creation form
        if ($req->isXmlHttpRequest()) {
            $meet = new Meeting();
            $form = $this->createForm(MeetingType::class, $meet);


            $form->handleRequest($req);

            if ($form->isSubmitted()) {
                $projectId = $req->get('project-id');
                return $this->handleForm($form, $meet, $projectId);
            }

            return $this->createMeetingForm($form);
        }

        throw new NotFoundHttpException("Not found");
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
    public function removeAction($meetingId, Request $req){
        
        if($req->isXmlHttpRequest()){
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
     * @param integer $projectId
     */
    private function handleForm(Form $form, Meeting $meet, $projectId) {

        $rep = new JsonResponse();

        if ($form->isValid()) {
            $p = $this->getProjectFromId($projectId);
            $meet->setProject($p);
            $this->saveMeeting($meet);
            $rep->setData(array(
                'success' => true,
                'meeting' => $meet
            ));
        } else {
            $rep->setData(array(
                'success' => false,
                'page' => $this->createMeetingForm($form)
            ));
        }

        return $rep;
    }

    /**
     * Save a meeting on the database
     * 
     * @param Meeting $meet
     */
    private function saveMeeting(Meeting &$meet) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($meet);
        $em->flush();
    }

    /**
     * Get a project from its id
     * 
     * @param int $projectId
     * @return Project
     */
    private function getProjectFromId($projectId) {
        return $this->getDoctrine()->getManager()->find(Project::class, $projectId);
    }

    /**
     * Create the meeting form from
     * the given form
     * 
     * @param Form $form
     * @return Response view
     */
    private function createMeetingForm(Form $form) {
        return $this->render('project/meetingForm.html.twig', array(
                    'form' => $form->createView()
        ));
    }

}
