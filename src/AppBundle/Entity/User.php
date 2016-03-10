<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @var id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @var string
     */
    private $username;

    /**
     * 
     * @ORM\Column(type="string", length=64)
     * 
     * @var string
     */
    private $password;

    /**
     * Knowing if this user is the admin
     * 
     * @ORM\Column(type="boolean")
     * @var boolean  
     */
    private $isAdmin;
    
    //All the non-saved informations
    
    /**
     * Not THE password, but the password
     * the user entered a second time when
     * registering
     * So, we don't need to save this into
     * the database
     *  
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     * 
     * @var string 
     */
    private $plainPassword;

    /**
     * Set if is admin
     * 
     * @param boolean $nwIsAdmin
     * @return User
     */
    public function setIsAdmin($nwIsAdmin){
        $this->isAdmin = $nwIsAdmin;
        
        return $this;
    }
    
    /**
     * Get is Admin
     * 
     * @return boolean
     */
    public function getIsAdmin(){
        return $this->isAdmin;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * The salt to add while hashing the password   
     * @todo : create salt from username
     * 
     * @return string
     */
    public function getSalt() {
        return null;
    }

    /**
     * Get roles
     * 
     * @return array
     */
    public function getRoles() {
        $roles = array('ROLE_USER');
        if($this->isAdmin){
            $roles[] = 'ROLE_ADMIN';
        }
        return $roles;
    }

    /**
     * Serialize an object
     * 
     * @return serialize object
     */
    public function serialize() {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isAdmin
        ));
    }

    public function unseralize($serialised) {
        list(
                $this->id,
                $this->username,
                $this->password,
                $this->isAdmin
                ) = unserialize($serialised);
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Get plain password
     * 
     * @return string
     */
    public function getPlainPassword() {
        return $this->plainPassword;
    }

    /**
     * Set plain password
     * 
     * @param string $plainPassword
     * @return User
     */
    public function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;

        return $this;
    }
    
    public function eraseCredentials()
    {
        
    }

}
