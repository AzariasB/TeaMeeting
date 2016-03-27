<?php

/*
 * This files contains the ProjectController class
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;

/**
 * This file handle all the project operation : 
 * deleting, changin, adding, displaying
 *
 * @author boutina
 */
class ProjectController extends SuperController {

    /**
     * Displays the project with id projId
     * and if the project is not locked
     * and the current user is also the leader
     * allow the editMode on the page
     * 
     * @param type $projId
     * @param Request $req
     * @return type
     * @throws NotFoundException
     */
    public function infoAction($projId, Request $req) {
        $proj = $this->getEntityFromId(Project::class, $projId);
        
        if (is_null($proj)) {
            throw $this->createNotFoundException('No project found for id ' . $projId);
        }

        $curUs = $this->getCurrentUser();
        if ($proj->getLeader() == $curUs && !$proj->getLocked()) {
            return $this->infoProjectPage($proj, true);
        }
        return $this->infoProjectPage($proj);
    }

    /**
     * Create the infopage
     * of a project
     * 
     * @param Project $project
     * @param boolean $canEdit
     * @return Response
     */
    private function infoProjectPage(Project $project,$canEdit = false) {
        return $this->render('project/presentation.html.twig', array(
                    'project' => $project,
                    'canEdit' => $canEdit
        ));
    }

    /**
     * Create a new project
     * 
     * @param Request $req
     * @return Response
     */
    public function createNewAction(Request $req) {
        $proj = new Project();
        $form = $this->createForm(ProjectType::class, $proj);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $req->isXmlHttpRequest()) {
            return $this->handleCreateXmlRequest($form, $proj);
        }
        return $this->createProjectPage($form);
    }

    /**
     * Check if the form is valid
     * and if the requirements are respected
     * (leader != secretary)
     * 
     * 
     * @param Form $form
     * @param Project $proj
     * @return JsonResponse
     */
    private function handleCreateXmlRequest(Form $form, Project $proj) {
        $resp = new JsonResponse();
        if ($form->isValid()) {
            if ($this->leaderIsSecretary($form)) {
                $form->get('leader')
                        ->addError(new FormError('The same personn cannot be leader and secretary'));
                $resp->setData(array(
                    'success' => false,
                    'newContent' => $this->createProjectPage($form)->getContent()
                ));
                return $resp;
            }
            $proj->setLocked(false);
            foreach ($proj->getRoles() as $r) {
                $r->setProject($proj);
            }
            $this->addParticipants($proj);
            $this->saveEntity($proj);
            $resp->setData(array(
                'success' => true,
                'projectId' => $proj->getId(),
                'projectName' => $proj->getProjectName()
            ));
            return $resp;
        }

        $resp->setData(array(
            'success' => false,
            'newContent' => $this->createProjectPage($form)->getContent()
        ));
        return $resp;
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
            $proj->addParticipant($leader);
        }
        if (!$parts->contains($secretary)) {
            $proj->addParticipant($secretary);
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
        $p = $this->getEntityFromId(Project::class, $proj);
        $success = false;
        if ($p->getLocked() !== $newState) {
            $p->setLocked($newState);
            $success = true;
            $this->saveEntity($p);
        }
        if ($success) {
            return $this->redirectToRoute('lobby');
        } else {
            //'Already locked,unlocked';
        }
        return $success;
    }
}
