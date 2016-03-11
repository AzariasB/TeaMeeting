<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 * @ORM\Entity
 */
class Project {

    /**
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     * @var string
     */
    private $projectName;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $locked;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @var int
     */
    private $leader;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @var int
     */
    private $secretary;

    /**
     * @ORM\OneToOne(targetEntity="ProjectRoles")
     * @var array
     */
    private $roles;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set project name
     * 
     * @param string $name
     * @return Project
     */
    public function setProjectName($name) {
        $this->projectName = $name;
        return $this;
    }

    /**
     * Get project Name
     * 
     * @return string
     */
    public function getProjectName() {
        return $this->projectName;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     * @return Project
     */
    public function setLocked($locked) {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean 
     */
    public function getLocked() {
        return $this->locked;
    }

    /**
     * Set leader
     *
     * @param integer $leader
     * @return Project
     */
    public function setLeader($leader) {
        $this->leader = $leader;

        return $this;
    }

    /**
     * Get leader
     *
     * @return integer 
     */
    public function getLeader() {
        return $this->leader;
    }

    /**
     * Set secretary
     *
     * @param integer $secretary
     * @return Project
     */
    public function setSecretary($secretary) {
        $this->secretary = $secretary;

        return $this;
    }

    /**
     * Get secretary
     *
     * @return integer 
     */
    public function getSecretary() {
        return $this->secretary;
    }

    /**
     * Set roles
     *
     * @param integer $roles
     * @return Project
     */
    public function setRoles($roles) {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return integer 
     */
    public function getRoles() {
        return $this->roles;
    }

}
