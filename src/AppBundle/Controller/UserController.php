<?php

/*
 * Contains the class UserController
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;

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
        
        if (!$u) {
            throw $this->createNotFoundException();
        }

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

}
