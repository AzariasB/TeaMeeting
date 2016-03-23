<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
 * Description of MeetingController
 *
 * @author boutina
 */
class MeetingController extends Controller {

    /**
     * 
     * @param Request $req
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

    private function saveMeeting(Meeting &$meet) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($meet);
        $em->flush();
    }

    /**
     * 
     * @param int $projectId
     * @return Project
     */
    private function getProjectFromId($projectId) {
        return $this->getDoctrine()->getManager()->find(Project::class, $projectId);
    }

    /**
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
