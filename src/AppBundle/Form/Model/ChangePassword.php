<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

/**
 * Description of ChangePassword
 *
 * @author boutina
 */
class ChangePassword {
    
    /**
     * @SecurityAssert\UserPassword(
     *      message = "Wrong value for your current password"
     * )
     * 
     * @var string
     */
    public $oldPassword;
    
    /**
     *
     * 
     * 
     * @var string 
     */
    public $newPassword;
    
}
