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
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use AppBundle\Form\ProjectRolesType;

/**
 * Description of ProjectController
 *
 * @author boutina
 */
class ProjectController extends Controller {

    public function infoAction($projId, Request $req) {
        $proj = $this->getProject($projId);
        if (!$proj) {
            throw $this->createNotFoundException('No project found for id ' . $projId);
        }

        $curUs = $this->getCurrentUser();
        if ($proj->getLeader() == $curUs && !$proj->getLocked()) {
            return $this->handleAddRolesForm($proj, $req);
        }
        return $this->infoProjectPage($proj);
    }

    /**
     * Handle the form
     * if new roles are added
     * to the project
     * 
     * @param Project $proj
     * @param Request $req
     * @return Response
     */
    private function handleAddRolesForm($proj, $req = null) {
        $form = $this->createForm(ProjectRolesType::class, $proj);

        if (!$req) {
            return $this->infoProjectPage($proj, $form->createView());
        }

        $origRoles = new ArrayCollection();
        foreach ($proj->getRoles() as $ro) {
            $origRoles->add($ro);
        }

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($proj->getRoles() as $role) {
                $role->setProject($proj);
            }
            $this->editRoles($proj, $origRoles);
            $this->saveProject($proj);
            return $this->handleAddRolesForm($proj);
        } else {
            return $this->infoProjectPage($proj, $form->createView());
        }
    }

    /**
     * 
     * @param Project $proj
     * @param ArrayCollection $origRoles
     */
    private function editRoles(&$proj, $origRoles) {
        $em = $this->getDoctrine()->getManager();
        foreach($origRoles as $role){
           if(!$proj->getRoles()->contains($role)){
               $proj->removeRole($role);
               $em->remove($role);
            }
        }
    }

    /**
     * Create the infopage
     * of a project
     * 
     * @param Project $project
     * @param FormView $formView
     * @return Response
     */
    private function infoProjectPage($project, $formView = null) {
        return $this->render('project/presentation.html.twig', array(
                    'project' => $project,
                    'form' => $formView
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
            $this->saveProject($proj);
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
    private function addParticipants(&$proj) {
        $parts = $proj->getParticipants();
        $leader = $proj->getLeader();
        $secretary = $proj->getSecretary();
        if (!$parts->contains($leader)) {
            $proj->addParticipants($leader);
        }
        if (!$parts->contains($secretary)) {
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
    private function saveProject($project) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();
    }

    /**
     * Get the current user
     * 
     * @return User
     */
    private function getCurrentUser() {
        return $this->get('security.token_storage')->getToken()->getUser();
    }

}
