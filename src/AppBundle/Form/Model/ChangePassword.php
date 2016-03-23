<?php

/*
 * Contains the ChangePassword class
 */

namespace AppBundle\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

/**
 * Tiny model to change a user's password from the old one
 * to the new one
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
