<?php

/**
 * Contains the defaults controller class
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;
use AppBundle\Form\Model\ChangePassword;
use AppBundle\Form\ChangePasswordType;

/**
 * This class if the default one, the 'first' one that is used
 * is is used to display the login page and the lobby page
 * It is also used to change a user's password ...
 * 
 * @todo move password changin to UserController
 */
class DefaultController extends SuperController {

    /**
     * @Route("/", name="homepage")
     * 
     * Check if the user is connected,
     * if he is redirect to lobby 
     * otherwise, show the login page
     * 
     * @param Request $request
     * @return Response
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

    /**
     * Show the lobby page. The twig templates will check
     * if the user is admin or not, and depending on that
     * will display a different page
     * 
     * @param Request $req
     * @return Response
     */
    public function lobbyAction(Request $req) {
        $em = $this->getDoctrine()->getManager();
        

        $users = $em->getRepository('AppBundle:User')->findAll();
        $projects = $em->getRepository('AppBundle:Project')->findAll();
        return $this->render('lobby/lobby.html.twig', array(
                    'users' => $users,
                    'projects' => $projects
        ));
    }

    /**
     * Show the form to change the password
     * and encode the new when the form is submitted
     * 
     * @param Request $req
     * @return Response
     */
    public function changePasswordAction(Request $req) {
        $changePasswordModel = new ChangePassword();
        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);

        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            return $this->handleResetPasswordForm($form);
        }

        return $this->renderResetForm($form);
    }

    /**
     * Handl the form to reset
     * the user's password
     * 
     * @param Form $form
     * @return JsonResponse
     */
    private function handleResetPasswordForm(Form $form) {
        $resp = new JsonResponse;
        if ($form->isValid()) {
            $user = $this->getCurrentUser();
            $nwPassword = $form->get('newPassword')->getData();
            $encrypted = $this->get('security.password_encoder')
                    ->encodePassword($user, $nwPassword);
            $user->setPassword($encrypted);

            $this->saveEntity($user);
            return $resp->setData(array(
                        'success' => true
            ));
        } else {
            return $resp->setData(array(
                        'success' => false,
                        'page' => $this->renderResetForm($form)
            ));
        }
    }

    private function renderResetForm(Form $form) {
        return $this->render('registration/resetpassword.html.twig', array(
                    'form' => $form->createView()
        ));
    }

}
