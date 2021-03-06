<?php

/*
 * Contains the Meeting entity
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use AppBundle\Entity\User;
use AppBundle\Entity\Agenda;
use Exception;
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
     * @ORM\Column(type="time")
     * @var DateTime
     */
    private $duration;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Project",inversedBy="meetings")
     * @ORM\JoinColumn(nullable=false)
     * @var Project
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="chairman_id",referencedColumnName="id")
     *
     * @var User
     */
    private $chairman;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="secretary_id",referencedColumnName="id")
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

    /**
     *
     * @ORM\OneToMany(targetEntity="Agenda",mappedBy="meeting",cascade={"persist","remove"})
     * 
     * @var ArrayCollection
     */
    private $agendas;

    /**
     * @ORM\OneToMany(targetEntity="MeetingMinute",mappedBy="meeting",cascade={"persist","remove"})
     *
     * @var ArrayCollection
     */
    private $minutes;

    public function __construct() {
        $this->answers = new ArrayCollection;
        $this->agendas = new ArrayCollection;
        $this->minutes = new ArrayCollection;
    }

    public function __toString() {
        return $this->room . '-' . $this->date->format('d/M/y');
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
     * Get duration
     * 
     * @return DateTime
     */
    public function getDuration() {
        return $this->duration;
    }

    /**
     * Set Duration
     * 
     * @param DateTime $dur
     * @return Meeting
     */
    public function setDuration(DateTime $dur) {
        $this->duration = $dur;
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
        return $this->chairman;
    }

    /**
     * Set chair man
     * 
     * @param User $chairman
     * @return Meeting
     */
    public function setChairMan(User $chairman) {
        $this->chairman = $chairman;
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
     * Get agendas
     * 
     * @return ArrayCollection
     */
    public function getAgendas() {
        return $this->agendas;
    }

    /**
     * Set agendas
     * 
     * @param ArrayCollection $agendas
     * @return Meeting
     */
    public function setAgendas(ArrayCollection $agendas) {
        $this->agendas = $agendas;
        return $this;
    }

    /**
     * Add agenda
     * 
     * @param Agenda $a
     * @return Meeting
     */
    public function addAgenda(Agenda $a) {
        $this->agendas->add($a);
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

    /**
     * Add answer
     * 
     * @param UserAnswer $answ
     * @return Meeting
     */
    public function addAnswer(UserAnswer $answ) {
        $this->answers->add($answ);
        return $this;
    }

    /**
     * Get meeting minutes
     * 
     * @return ArrayCollection
     */
    public function getMinutes() {
        return $this->minutes;
    }

    /**
     * Set meeting minutes
     * 
     * @param ArrayCollection $minutes
     * @return Meeting
     */
    public function setMinutes(ArrayCollection $minutes) {
        $this->minutes = $minutes;
        return $this;
    }
    
    /**
     * Add minute
     * 
     * @param MeetingMinute $nwMinute
     * @return Meeting
     */
    public function addMinute(MeetingMinute $nwMinute){
        $this->minutes->add($nwMinute);
        return $this;
    }

    /**
     * Returns the current meeting minute
     * if the meeting is not outdated
     * otherwise, returns null.
     * 
     * @return MeetingMinute|null
     */
    public function getCurrentMinute() {
        if (!$this->isOutdated()) {
            return null;
        } else {
            if ($this->minutes->isEmpty()) {
                return null;
            }
            return $this->minutes->last();
        }
    }

    /**
     * Get the last agenda
     * 
     * @return Agenda
     * @throws Exception
     */
    public function getCurrentAgenda() {
        if ($this->agendas->isEmpty()) {
            throw new Exception("There should be at least one agenda");
        }
        return $this->agendas->last();
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'room' => $this->room,
            'date' => $this->date->getTimestamp() * 1000,
            'outdated' => $this->isOutdated(),
            'duration' => $this->duration,
            'chairMan' => $this->chairman,
            'secretary' => $this->secretary
        );
    }

    /**
     * Set the participants of the meeting
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

    /**
     * Get the answer given by the user
     * 
     * @param User $user
     * @return UserAnswer
     */
    public function answerForUser(User $user) {

        $res = $this->answers->filter(function($answer)use($user) {
            return $user == $answer->getUser();
        });
        if ($res->count() === 0) {
            return null;
        } else {
            return $res->first();
        }
    }

    /**
     * Return true if the meeting is outdated
     * 
     * @return boolean
     */
    public function isOutdated() {
        return $this->date < new \DateTime;
    }

    /**
     * Check wether the given user is the secretary of the meeting
     * 
     * @param User $user
     * @return boolean
     */
    public function userIsSecretary(User $user){
        return $this->secretary == $user;
    }
    
    /**
     * Check wether the given user is the chairman of the meeting
     * 
     * @param User $user
     * @return boolean
     */
    public function userIsChairMan(User $user){
        return $this->chairman == $user;
    }
    
    /**
     * Number of answers 'no' for the meeting
     * 
     * @return int
     */
    public function numberOfNo() {
        return $this->numberOf(UserAnswer::ANSWER_NO);
    }

    /**
     * Number of answers 'yes' for the meeting
     * 
     * @return int
     */
    public function numberOfYes() {
        return $this->numberOf(UserAnswer::ANSWER_YES);
    }

    /**
     * Number of answers 'maybe' for the meeting
     * 
     * @return int
     */
    public function numberOfMaybe() {
        return $this->numberOf(UserAnswer::ANSWER_MAYBE);
    }

    /**
     * Number of answers 'not-answered' for the metting
     * 
     * @return int
     */
    public function numberOfUnknown() {
        return $this->numberOf(UserAnswer::NO_ANSWER);
    }

    /**
     * Count the number of 'answertype' contained in
     * the answers array
     * 
     * @param int $answerType
     * @return int
     */
    private function numberOf($answerType) {
        return $this->answers->filter(function($ans) use($answerType) {
                    return $ans->getAnswer() === $answerType;
                })->count();
    }

}
