<?php

/*
 * Contains the class RegisterController
 */

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Used to register a user
 * 
 * @todo : move to UserController
 *
 * @author boutina
 */
class RegisterController extends SuperController {

    /**
     * @Route("/register", name="user_registration")
     * 
     * Register a user
     * 
     * @param Request $request the Request
     */
    public function registerAction(Request $request) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isSubmitted()) {
            $resp = new JsonResponse();
            if ($form->isValid()) {
                /**
                 * Since randombyte return hard-to-type chars,
                 * we have to encode this in base64,
                 */
                $randPwd = base64_encode(random_bytes(6));

                $password = $this->get('security.password_encoder')
                        ->encodePassword($user, $randPwd);
                $user->setPassword($password);
                $user->setIsAdmin(false);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $resp->setData(array(
                    'success' => true,
                    'password' => $randPwd,
                    'username' => $user->getUsername(),
                    'userId' => $user->getId())
                );
            } else {
                $resp->setData(array(
                    'success' => false,
                    'error' => $form->getErrors())
                );
            }
            return $resp;
        }
        return $this->render('registration/register.html.twig', array(
                    'form' => $form->createView())
        );
    }

}
