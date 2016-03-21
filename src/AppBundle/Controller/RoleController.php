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
use AppBundle\Entity\UserRole;
use AppBundle\Entity\Project;
use AppBundle\Form\RoleType;
use Symfony\Component\Form\Form;

/**
 * Description of RoleController
 *
 * @author boutina
 */
class RoleController extends Controller {

    /**
     * 
     * @param Request $req
     */
    public function createAction(Request $req) {

        // Display creation form
        if ($req->isXmlHttpRequest()) {
            $projectId = $req->get('project-id');
            $p = $this->getProjectFromId($projectId);
            $role = new UserRole();
            $role->setProject($p);
            $form = $this->createForm(RoleType::class, $role);


            $form->handleRequest($req);

            if ($form->isSubmitted()) {
                return $this->handleForm($form, $role);
            }

            return $this->createRoleForm($form);
        }

        throw new NotFoundHttpException("Not found");
    }

    public function removeAction($roleId, Request $req) {

        if ($req->isXmlHttpRequest()) {
            $rep = new JsonResponse();
            $em = $this->getDoctrine()->getManager();
            $role = $em->find(UserRole::class, $roleId);
            $em->remove($role);
            $em->flush();
            return $rep->setData(array('success' => true));
        }

        throw new NotFoundHttpException("Not found");
    }

    /**
     * 
     * @param Form $form
     * @param UserRole $role
     * @param integer $projectId
     */
    private function handleForm(Form $form, UserRole $role) {

        $rep = new JsonResponse();

        if ($form->isValid()) {
            $this->saveRole($role);
            $rep->setData(array(
                'success' => true,
                'role' => $role
            ));
        } else {
            $rep->setData(array(
                'success' => false,
                'page' => $this->createRoleForm($form)
            ));
        }

        return $rep;
    }

    /**
     * 
     * @param UserRole $role
     */
    private function saveRole(UserRole &$role) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($role);
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
    private function createRoleForm(Form $form) {
        return $this->render('project/roleForm.html.twig', array(
                    'form' => $form->createView()
        ));
    }

}
