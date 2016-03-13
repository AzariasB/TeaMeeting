<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;

/**
 * Description of UserController
 *
 * @author boutina
 */
class UserController extends Controller {

    
    public function infoAction($user){
        $u = $this->getUserFromId($user);
        $roles = $this->getUserProject($user);
        return $this->render('user/profile.html.twig',array(
            'user' => $u,
            'roles' => $roles
        ));
    }
    
    /**
     * Get the user from its id
     * 
     * @param integer $userId
     * @return User
     */
    private function getUserFromId($userId){
       return $this->getDoctrine()->getManager()->find(User::class, $userId);
    }
    
    private function getUserProject($userId){
        $rep = $this->getDoctrine()->getManager()->getRepository('AppBundle:UserRole');
        $roles = $rep->findBy(array(
            'student' => $userId
        ));
        return $roles;
    }
}
