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

            $em = $this->getDoctrine()->getManager();
            $em->persist($proj);
            $em->flush();
            return $this->render('project/created.html.twig');
        }

        return $this->createProjectPage($form);
    }

    /**
     * Return the page created from
     * the given form
     * 
     * @param From $form
     * @return Response
     */
    private function createProjectPage($form) {
        return $this->renderCreate('project/create.html.twig', array(
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
    private function saveProject($project) {
        $em = $this->getDoctrine()->getManager();
        $em->merge($project);
        $em->flush();
    }

}
