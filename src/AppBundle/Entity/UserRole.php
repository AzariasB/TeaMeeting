<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;

/**
 * UserRole
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
     * @param User $studentId
     * @return UserRole
     */
    public function setStudent($studentId) {
        $this->student = $studentId;

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
     * @param Project $projectId
     * @return UserRole
     */
    public function setProject($projectId) {
        $this->project = $projectId;
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
            'student' => $this->student,
            'project' => $this->project
        );
    }

}
