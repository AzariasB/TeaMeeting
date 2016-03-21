<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Description of Meeting
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
     * @ORM\Column(type="date")
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
            'room' => $this->room,
            'date' => json_encode($this->date),
            'project' => json_encode($this->project)
        );
    }

}
