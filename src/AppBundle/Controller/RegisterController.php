<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of RegisterController
 *
 * @author boutina
 */
class RegisterController extends Controller {

    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request) {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setIsAdmin(false);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            //TODO : return to connection page or something
            return $this->redirectToRoute('home');
        }
        
        return $this->render(
                'registration/register.html.twig',
                array('form' => $form->createView())
                );
    }

}
