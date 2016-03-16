<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use AppBundle\Form\ProjectRolesType;

/**
 * Description of MeetingController
 *
 * @author boutina
 */
class MeetingController extends Controller {

    public function createAction(Request $req){
        // Display creation form
    }
}
