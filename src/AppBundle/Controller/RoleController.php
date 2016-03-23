<?php

/*
 * Contains the roleController
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
 * Handle all the roles actions :
 * delete,create,update.
 *
 * @author boutina
 */
class RoleController extends Controller {

    /**
     * Creates a new Role
     * the request must be made
     * with ajax otherwise notfound is thrown
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

    /**
     * Remove the role with id 'roleId'
     * from the dataBase
     * the request must be made in ajax
     * 
     * @param type $roleId
     * @param Request $req
     * @return JsonReponse
     * @throws NotFoundHttpException
     */
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
     * Handle the form to create a role
     * and check wether the form is valid
     * 
     * @param Form $form
     * @param UserRole $role
     * @param integer $projectId
     * @return JsonResponse
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
     * Save a role in the databse
     * 
     * @param UserRole $role
     */
    private function saveRole(UserRole &$role) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($role);
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
     * Return the form view 
     * to create a role
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
