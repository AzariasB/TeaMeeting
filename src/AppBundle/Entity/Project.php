<?php

/**
 * Contains the Project entity
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\UserRole;
use AppBundle\Entity\User;

/**
 * A Project is one of the main components of the application
 * A project has participants. Each can have multiple roles.
 * A project also has meetings, a leader and a secretary
 * 
 * 
 * @ORM\Entity
 */
class Project implements \JsonSerializable {

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
     * @var User
     */
    private $leader;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @var User
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
     * @ORM\OrderBy({"date" = "ASC"})
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
     * @param User $leader
     * @return Project
     */
    public function setLeader(User $leader) {
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
     * @param User $secretary
     * @return Project
     */
    public function setSecretary(User $secretary) {
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
    public function setRoles(ArrayCollection $roles) {
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
    public function removeRole(UserRole $r) {
        $this->roles->removeElement($r);
        return $this;
    }

    /**
     * Add role
     * 
     * @param UserRole $r
     * @return Project
     */
    public function addRole(UserRole $r) {
        $this->roles->add($r);
        return $this;
    }

    /**
     * Set participants
     * 
     * @param ArrayCollection $parts
     * @return Project
     */
    public function setParticipants(ArrayCollection $parts) {
        $this->participants = $parts;

        return $this;
    }

    /**
     * Get participants
     * 
     * @return ArrayCollection
     */
    public function getParticipants() {
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
    public function addParticipant(User $parts) {
        $this->participants->add($parts);

        return $this;
    }

    /**
     * Remove a participant
     * 
     * @param User $part
     * @return Project
     */
    public function removeParticipant(User $part) {
        $this->participants->removeElement($part);
        return $this;
    }

    /**
     * Set meetings
     * 
     * @param ArrayCollection $meetings
     * @return Project
     */
    public function setMeetings(ArrayCollection $meetings) {
        $this->meetings = $meetings;
        return $this;
    }

    /**
     * Get meetings
     * 
     * @return ArrayCollection
     */
    public function getMeetings() {
        return $this->meetings;
    }

    /**
     * Add a meeting
     * 
     * @param Meeting $me
     * @return Project
     */
    public function addMeeting(Meeting $me) {
        $this->meetings->add($me);
        return $this;
    }

    /**
     * Remove a meeting
     * 
     * @param Meeting $met
     * @return Project
     */
    public function removeMeeting(Meeting $met) {
        $this->meetings->removeElement($met);
        return $this;
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'name' => $this->projectName,
            'locked' => $this->locked,
            'meetings' => $this->meetings->toArray(),
            'roles' => $this->roles->toArray(),
            'participants' => $this->participants->toArray(),
            'leader' => $this->leader,
            'secretary' => $this->secretary
        );
    }

}
