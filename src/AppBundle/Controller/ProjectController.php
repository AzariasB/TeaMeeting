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

    public function getAllAction(Request $req) {
        $projects = $this->getAllFromClass(Project::class);
        $resp = new JsonResponse;
        return $resp->setData($projects);
    }

    /**
     * Show the page with all the projects
     * (projects loaded asynchronously)
     * 
     * @param Request $req
     * @return Response
     */
    public function allAction(Request $req) {
        return $this->render('lobby/allprojects.html.twig');
    }

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

    public function getJsonAction($projId, Request $req) {
        $proj = $this->getEntityFromId(Project::class, $projId);
        $rep = new JsonResponse;
        return $rep->setData($proj);
    }

    /**
     * Create the infopage
     * of a project
     * 
     * @param Project $project
     * @param boolean $canEdit
     * @return Response
     */
    private function infoProjectPage(Project $project, $canEdit = false) {
        return $this->render('project/presentation.html.twig', array(
                    'project' => $project,
                    'canEdit' => $canEdit,
                    'breadcrumbs' => $this->generateBreadCrumbs($project)
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
        if ($form->isSubmitted()) {
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
                'project' => $proj
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
    public function deleteAction($projId, Request $req) {
        $this->deleteProject($projId);
        $resp = new JsonResponse;
        return $resp->setData(array(
                    'success' => true,
                    'projectId' => $projId
        ));
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
        return $this->render('project/project-form.html.twig', array(
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
    public function lockAction($proj, Request $req) {
        return $this->changeProjStateTo($proj, true, $req);
    }

    /**
     * Unlock the project
     * 
     * @param integer $proj
     * @return Response
     */
    public function unlockAction($proj, Request $req) {
        return $this->changeProjStateTo($proj, false, $req);
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
    private function changeProjStateTo($proj, $newState, Request $rq) {
        $p = $this->getEntityFromId(Project::class, $proj);
        $success = false;
        if ($p->getLocked() !== $newState) {
            $p->setLocked($newState);
            $success = true;
            $this->saveEntity($p);
        }

        if ($rq->isXmlHttpRequest()) {
            $res = new JsonResponse;
            return $res->setData(array(
                        'success' => $success,
                        'project' => $p
            ));
        } else {
            $referer = $rq->headers->get('referer');
            return $this->redirect($referer);
        }
    }

}
