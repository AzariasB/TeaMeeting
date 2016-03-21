<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface, \JsonSerializable {

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
    
    /**
     * @ORM\ManyToMany(targetEntity="Project",mappedBy="participants",cascade={"persist","remove"})
     * @var ArrayCollection
     */
    private $projects;
    

    
    public function __construct() {
        $this->projects = new ArrayCollection();
    }
    
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
     * 
     * @return string
     */
    public function getSalt() {
        /**
         * REturn a 22 chars long salt
         */
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
            $this->isAdmin,
            $this->projects
        ));
    }

    public function unseralize($serialised) {
        list(
                $this->id,
                $this->username,
                $this->password,
                $this->isAdmin,
                $this->projects
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
     * Set projects
     * 
     * @param ArrayCollection $projs
     * @return User
     */
    public function setProject($projs){
        $this->projects = $projs;
        
        return $this;
    }
    
    /**
     * Get projects
     * 
     * @return ArrayCollection
     */
    public function getProjects(){
        return $this->projects;
    }

    
    public function eraseCredentials()
    {
        
    }

    public function jsonSerialize() {
        return array(
           'id' => $this->id,
            'name' => $this->username,
            'isAdmin' => $this->isAdmin
        );
    }

}
