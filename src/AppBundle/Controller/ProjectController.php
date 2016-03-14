<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;

/**
 * Description of ProjectController
 *
 * @author boutina
 */
class ProjectController extends Controller {

    public function infoAction($proj) {
        $obj = $this->getProject($proj);
        return $this->render('project/presentation.html.twig', array(
                    'project' => $obj
        ));
    }

    public function createNewAction(Request $req) {
        $proj = new Project();

        $form = $this->createForm(ProjectType::class, $proj);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($this->leaderIsSecretary($form)) {
                $form->get('leader')
                        ->addError(new FormError('The same personn cannot be leader and secretary'));
                return $this->createProjectPage($form);
            }
            $proj->setLocked(false);
            foreach ($proj->getRoles() as $r) {
                $r->setProject($proj);
            }
            $this->addParticipants($proj);
            $this->createProject($proj);
            return $this->render('project/created.html.twig');
        }

        return $this->createProjectPage($form);
    }

    /**
     * Route to delete a project
     * 
     * @param int $projId
     * @return Response
     */
    public function deleteAction($projId) {
        $this->deleteProject($projId);
        return $this->redirectToRoute('lobby');
    }

    /**
     * Add the leader and the
     * secretary to the participants
     * if not already done
     * 
     * @param Project $proj
     */
    private function addParticipants(&$proj){
        $parts = $proj->getParticipants();
        $leader = $proj->getLeader();
        $secretary = $proj->getSecretary();
        if(!$parts->contains($leader)){
            $proj->addParticipants($leader);
        }
        if(!$parts->contains($secretary)){
            $proj->addParticipants($secretary);
        }
    }
    
    /**
     * Delete the project
     * 
     * @param integer $projId
     */
    private function deleteProject($projId) {
        $em = $this->getDoctrine()->getManager();
        $p = $em->find(Project::class, $projId);
        if ($p) {
            $em->remove($p);
            $em->flush();
        }
    }

    /**
     * Create the project
     * 
     * @param Project $proj
     */
    private function createProject($proj) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($proj);
        $em->flush();
    }

    /**
     * Return the page created from
     * the given form
     * 
     * @param From $form
     * @return Response
     */
    private function createProjectPage($form) {
        return $this->render('project/create.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * If, when the form is submitted
     * the user selected to be the leader
     * is also selected to be the secretary
     * 
     * @param Form $form
     * @return boolean
     */
    private function leaderIsSecretary($form) {
        $leader = $form->get('leader')->getData();
        $secreatary = $form->get('secretary')->getData();
        return $leader->getId() === $secreatary->getId();
    }

    /**
     * 
     * Lock the project
     * 
     * @param integer $proj
     * @return Response
     */
    public function lockAction($proj) {
        return $this->changeProjStateTo($proj, true);
    }

    /**
     * Unlock the project
     * 
     * @param integer $proj
     * @return Response
     */
    public function unlockAction($proj) {
        return $this->changeProjStateTo($proj, false);
    }

    /**
     * 
     * Change the state of the project to newState
     * If the project already had this state,
     * return false, else return true
     * 
     * @param integer $proj
     * @param boolean $newState
     * @return boolean
     */
    private function changeProjStateTo($proj, $newState) {
        $p = $this->getProject($proj);
        $success = false;
        if ($p->getLocked() !== $newState) {
            $p->setLocked($newState);
            $success = true;
            $this->saveProject($p);
        }
        if ($success) {
            return $this->redirectToRoute('lobby');
        } else {
            //return $this->redirectToRoute('Already locked,unlocked');
        }
        return $success;
    }

    /**
     * Get the project
     * with the given id
     * 
     * @param integer $projectId
     * @return Project
     */
    private function getProject($projectId) {
        return $this->getDoctrine()->getManager()->find(Project::class, $projectId);
    }

    /**
     * Persist the project
     * 
     * @param Project $project
     */
    private function saveProject(&$project) {
        $em = $this->getDoctrine()->getManager();
        $em->merge($project);
        $em->flush();
    }

}
