<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\Model\ChangePassword;
use AppBundle\Form\ChangePasswordType;

class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            //User already conneted => to the lobby 
            return $this->redirectToRoute('lobby');
        }

        return $this->render('default/index.html.twig', array(
                    'last_username' => $lastUsername,
                    'error' => $error,
        ));
    }

    public function lobbyAction(Request $req) {
        $em = $this->getDoctrine()->getManager();
        $users =  $em->getRepository('AppBundle:User')->findAll();
        $projects = $em->getRepository('AppBundle:Project')->findAll();
        return $this->render('default/lobby.html.twig',array(
            'users' => $users,
            'projects' => $projects
        ));
    }

    public function changePasswordAction(Request $req) {
        $changePasswordModel = new ChangePassword();
        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getCurrentUser();
            // perform some action,
            // such as encoding with MessageDigestPasswordEncoder and persist
            $nwPassword = $form->get('newPassword')->getData();
            $encrypted = $this->get('security.password_encoder')
                    ->encodePassword($user, $nwPassword);
            $user->setPassword($encrypted);

            $em = $this->getDoctrine()->getManager();
            $em->merge($user);
            $em->flush();
            return $this->render('registration/passwordchanged.html.twig');
        }

        return $this->render('registration/resetpassword.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * Get the current user
     * 
     * @return User
     */
    private function getCurrentUser() {
        return $this->get('security.token_storage')->getToken()->getUser();
    }

}
