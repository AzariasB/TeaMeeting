<?php

/*
 * Contains the Meeting entity
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;

/**
 * 
 * The meeting is a part of the project
 * a project can have several meetings, and the meeting contains
 * agendas
 *
 * @author boutina
 * @ORM\Entity
 */
class Meeting implements \JsonSerializable {

    /**
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     *
     * @ORM\Column(type="datetime")
     * 
     * @var Date Date AND hour of the meeting
     */
    private $date;

    /**
     *
     * @ORM\Column(type="string")
     * 
     * @var string
     */
    private $room;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Project",inversedBy="meetings")
     * @ORM\JoinColumn(nullable=false)
     * @var Project
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     *
     * @var User
     */
    private $chairMan;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     *
     * @var User
     */
    private $secretary;

    /**
     * @ORM\OneToMany(targetEntity="UserAnswer",mappedBy="meeting",cascade={"persist","remove"})
     * 
     * @var ArrayCollection
     */
    private $answers;

    public function __construct() {
        $this->answers = new ArrayCollection;
    }

    /**
     * Get id
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set id
     * 
     * @param int $id
     * @return Meeting
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Get date
     * 
     * @return DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set date
     * 
     * @param DateTime $date
     * @return Meeting
     */
    public function setDate(DateTime $date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get room
     * 
     * @return string
     */
    public function getRoom() {
        return $this->room;
    }

    /**
     * Set room
     * 
     * @param string $room
     * @return Meeting
     */
    public function setRoom($room) {
        $this->room = $room;
        return $this;
    }

    /**
     * Set project
     * 
     * @param Project $proj
     * @return Meeting
     */
    public function setProject(Project $proj) {
        $this->project = $proj;
        $this->setParticipantsAnswers($proj->getParticipants());
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

    /**
     *  Get chair man
     * 
     * @return User
     */
    public function getChairMan() {
        return $this->chairMan;
    }

    /**
     * Set chair man
     * 
     * @param User $chairman
     * @return Meeting
     */
    public function setChairMan(User $chairman) {
        $this->chairMan = $chairman;
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
     * Set secretary
     * 
     * @param User $secr
     * @return Meeting
     */
    public function setSecretary(User $secr) {
        $this->secretary = $secr;
        return $this;
    }

    /**
     * Get answers
     * 
     * @return ArrayCollection
     */
    public function getAnswers() {
        return $this->answers;
    }

    /**
     * Set answers
     * 
     * @param ArrayCollection $answers
     */
    public function setAnswers(ArrayCollection $answers) {
        $this->answers = $answers;
        return $this;
    }

    public function addAnswer(UserAnswer $answ) {
        $this->answers->add($answ);
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'room' => $this->room,
            'date' => $this->date,
            'project' => $this->project,
            'chairMan' => $this->chairMan,
            'secretary' => $this->secretary
        );
    }

    /**
     * 
     * @param ArrayCollection $participants
     */
    public function setParticipantsAnswers(Collection $participants) {
        $this->answers->clear();
        foreach ($participants as $part) {
            $ans = new UserAnswer();
            $ans->setUser($part);
            $ans->setMeeting($this);
            $this->addAnswer($ans);
        }
    }

    public function numberOfNo() {
        return $this->numberOf(UserAnswer::ANSWER_NO);
    }

    public function numberOfYes() {
        return $this->numberOf(UserAnswer::ANSWER_YES);
    }

    public function numberOfMaybe() {
        return $this->numberOf(UserAnswer::ANSWER_MAYBE);
    }

    public function numberOfUnknown() {
        return $this->numberOf(UserAnswer::NO_ANSWER);
    }

    private function numberOf($answerType) {
        return $this->answers->filter(function($ans) use($answerType) {
                    return $ans->getAnswer() === $answerType;
                })->count();
    }

}
