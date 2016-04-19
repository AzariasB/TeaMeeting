<?php

/**
 * Contains the UserRole Entity
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;

/**
 * UserRole is a simple entity to represent the roles of a project
 * a UserRole contains a User and a role name and is associated with a project
 * 
 * @ORM\Entity
 */
class UserRole implements \JsonSerializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=64)
     * @var string
     */
    private $roleName;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     * @var User
     */
    private $student;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="roles")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id",nullable=false)
     * @var Project
     */
    private $project;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set id
     * 
     * @param type $nwId
     * @return UserRole
     */
    public function setId($nwId){
        $this->id = $nwId;
        return $this;
    }
    
    /**
     * Set roleName
     *
     * @param string $roleName
     * @return UserRole
     */
    public function setRoleName($roleName) {
        $this->roleName = $roleName;

        return $this;
    }

    /**
     * Get roleName
     *
     * @return string 
     */
    public function getRoleName() {
        return $this->roleName;
    }

    /**
     * Set student
     *
     * @param User $student
     * @return UserRole
     */
    public function setStudent(User $student) {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return integer 
     */
    public function getStudent() {
        return $this->student;
    }

    /**
     * Set project
     * 
     * @param Project $project
     * @return UserRole
     */
    public function setProject(Project $project) {
        $this->project = $project;
        return $this;
    }

    /**
     * Get project
     * 
     * @return Project 
     */
    public function getProject() {
        return $this->project;
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'name' => $this->roleName,
            'student' => $this->student
        );
    }

}
