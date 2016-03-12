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
use AppBundle\Entity\UserRole;
use AppBundle\Form\ProjectType;
use AppBundle\Form\RoleType;

/**
 * Description of ProjectController
 *
 * @author boutina
 */
class ProjectController extends Controller {

    public function createNewAction(Request $req) {
        $proj = new Project();

        $role1 = new UserRole();
        $role1->setRoleName('Painter');
        $proj->getRoles()->add($role1);

        $role2 = new UserRole();
        $role2->setRoleName('Lecturer');
        $proj->getRoles()->add($role2);

        $form = $this->createForm(ProjectType::class, $proj);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $samePerson = $form->get('leader')
                            ->getData()
                            ->getId() === $form->get('secretary')
                            ->getData()
                            ->getId();

            if ($samePerson) {
                $form
                        ->get('leader')
                        ->addError(new FormError('The same personn cannot be leader and secretary'));
                return $this->render(
                                'project/create.html.twig', array('form' => $form->createView())
                );
            }
            $proj->setLocked(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($proj);
            $em->flush();
            $role1->setProject($proj);
            $em->merge($role1);
            $role2->setProject($proj);
            $em->merge($role2);
            $em->flush();

            return $this->render('project/created.html.twig');
        }

        return $this->render(
                        'project/create.html.twig', array('form' => $form->createView())
        );
    }

    public function lockAction($proj) {
        if ($this->changeProjStateTo($proj, true)) {
            return $this->redirectToRoute('lobby');
        } else {
            //return $this->redirect(error);
        }
    }

    public function unlockAction($proj) {
        if($this->changeProjStateTo($proj, false)){
            return $this->redirectToRoute('lobby');
        }else{
            //return $this->redirect(error);
        }
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
        }
        $this->saveProject($p);
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
