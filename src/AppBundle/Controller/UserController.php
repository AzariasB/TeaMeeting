<?php

/*
 * Contains the class UserController
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Manage the user's CRUD operations
 * 
 *
 * @author boutina
 */
class UserController extends SuperController {

    /**
     * Shows the profile of a user
     * this is NOT the lobby route, anyone can see
     * the profile of a user, this function is
     * the one which returns the informations
     * about the user with id 'id'
     * 
     * @param integer $user
     * @return type
     */
    public function infoAction($user) {
        $u = $this->getEntityFromId(User::class, $user);

        $roles = $this->getUserProject($user);
        return $this->render('user/profile.html.twig', array(
                    'user' => $u,
                    'roles' => $roles
        ));
    }

    /**
     * 
     * @param type $userId
     * @return type
     */
    private function getUserProject($userId) {
        $rep = $this->getDoctrine()->getManager()->getRepository('AppBundle:UserRole');
        $roles = $rep->findBy(array(
            'student' => $userId
        ));
        return $roles;
    }

    /**
     * Reset the password of the user with the given
     * id
     * Can only be called by the admin
     * 
     * 
     * @param type $userId
     * @param Request $req
     * @return JsonResponse
     */
    public function resetPasswordAction($userId, Request $req) {
        $this->assertAdmin();
        $user = $this->getEntityFromId(User::class, $userId);
        $randPwd = base64_encode(random_bytes(6));

        $password = $this->get('security.password_encoder')
                ->encodePassword($user, $randPwd);
        $user->setPassword($password);
        $this->saveEntity($user);

        return new JsonResponse(['success' => true,'password' => $randPwd]);
    }

    /**
     * Return all the users in the database
     * 
     * @param Request $req
     * @return JsonResponse
     */
    public function getAllAction(Request $req) {
        $this->assertAdmin();
        $users = $this->getAllFromClass(User::class);
        $resp = new JsonResponse;
        return $resp->setData($users);
    }

    /**
     * Returns the page to display all the users
     * the user list is returned asynchronously
     * 
     * @param Request $req
     * @return Response
     */
    public function allAction(Request $req) {
        return $this->render('lobby/allusers.html.twig');
    }

}
