<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use Symfony\Component\Form\FormError;

/**
 * Description of ProjectController
 *
 * @author boutina
 */
class ProjectController extends Controller {

    public function createNewAction(Request $req) {
        $proj = new Project();
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
            return $this->render('project/created.html.twig');
        }

        return $this->render(
                        'project/create.html.twig', array('form' => $form->createView())
        );
    }

}
