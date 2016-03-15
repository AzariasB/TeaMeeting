<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\UserRole;

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
     * @ORM\OneToMany(targetEntity="UserRole",mappedBy="project",cascade={"persist","remove"})
     * @var ArrayCollection
     */
    private $roles;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="Meeting",mappedBy="project",cascade={"persist","remove"})
     * @var ArrayCollection
     */
    private $meetings;
    
    /**
     *
     * @ORM\ManyToMany(targetEntity="User",inversedBy="projects")
     * @ORM\JoinTable(name="users_projects")
     * @var ArrayCollection
     */
    private $participants;

    
    public function __construct() {
        $this->roles = new ArrayCollection();
        $this->participants = new ArrayCollection();
        $this->meetings = new ArrayCollection();
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
     * @return User 
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
     * @return User 
     */
    public function getSecretary() {
        return $this->secretary;
    }

    /**
     * Set roles
     *
     * @param ArrayCollection $roles
     * @return Project
     */
    public function setRoles($roles) {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return ArrayCollection 
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * Remove role
     * 
     * @param UserRole $r
     */
    public function removeRole(UserRole $r){
        $this->roles->removeElement($r);
    }
    
    /**
     * Set participants
     * 
     * @param ArrayCollection $parts
     * @return Project
     */
    public function setParticipants($parts){
        $this->participants = $parts;
        
        return $this;
    }
    
    /**
     * Get participants
     * 
     * @return ArrayCollection
     */
    public function getParticipants(){
        return $this->participants;
    }
    
    /**
     * 
     * Add participant to
     * the participants list
     * 
     * @param User $parts
     * @return Project
     */
    public function addParticipants($parts){
        $this->participants->add($parts);
        
        return $this;
    }
    
    /**
     * Set meetings
     * 
     * @param ArrayCollection $meetings
     * @return Project
     */
    public function setMeetings(ArrayCollection $meetings){
        $this->meetings = $meetings;
        return $this;
    }
    
    /**
     * Get meetings
     * 
     * @return ArrayCollection
     */
    public function getMeetings(){
        return $this->meetings;
    }    
    
    /**
     * Add a meeting
     * 
     * @param Meeting $me
     * @return Project
     */
    public function addMeeting(Meeting $me){
        $this->meetings->add($me);
        return $this;
    }
    
    /**
     * Remove a meeting
     * 
     * @param Meeting $met
     * @return Project
     */
    public function removeMeeting(Meeting $met){
        $this->meetings->remove($met);
        return $this;
    }
}
