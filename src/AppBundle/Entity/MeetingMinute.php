<?php

/*
 * The MIT License
 *
 * Copyright 2016 boutina.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Contains the class MeetingMinute
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of MeetingMinute
 *
 * @author boutina
 * @ORM\Entity
 */
class MeetingMinute implements \JsonSerializable {

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
     * @ORM\OneToMany(targetEntity="UserPresence",mappedBy="meetingMinute",cascade={"persist","remove"})
     * @var ArrayCollection 
     */
    private $presenceList;

    /**
     * @ORM\OneToMany(targetEntity="ItemMinute",mappedBy="meetingMinute",cascade={"persist","remove"})
     * @var ArrayCollection
     */
    private $items;

    /**
     * @ORM\OneToOne(targetEntity="Agenda")
     * @ORM\JoinColumn(name="agenda_id", referencedColumnName="id")
     * @var Agenda
     */
    private $agenda;

    /**
     *
     * @ORM\OneToMany(targetEntity="MinuteComment",mappedBy="meetingMinute",cascade={"persist","remove"})
     * @var ArrayCollection
     */
    private $comments;

    public function __construct() {
        $this->presenceList = new ArrayCollection;
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
     * Get presence list
     * 
     * @return ArrayCollection
     */
    public function getPresenceList() {
        return $this->presenceList;
    }

    /**
     * Get items
     * 
     * @return ArrayCollection
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * Get agenda
     * 
     * @return Agenda
     */
    public function getAgenda() {
        return $this->agenda;
    }

    /**
     * Get comments
     * 
     * @return ArrayCollection
     */
    public function getComments() {
        return $this->comments;
    }

    /**
     * Set id
     * 
     * @param int $id
     * @return MeetingMinute
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Set presence list
     * 
     * @param ArrayCollection $presenceList
     * @return MeetingMinute
     */
    public function setPresenceList(ArrayCollection $presenceList) {
        $this->presenceList = $presenceList;
        return $this;
    }

    /**
     * Set items
     * 
     * @param ArrayCollection $items
     * @return MeetingMinute
     */
    public function setItems(ArrayCollection $items) {
        $this->items = $items;
        return $this;
    }

    /**
     * Set agenda
     * 
     * @param Agenda $agenda
     * @return MeetingMinute
     */
    public function setAgenda($agenda) {
        $this->agenda = $agenda;
        return $this;
    }

    /**
     * Set comments
     * 
     * @param ArrayCollection $comments
     * @return MeetingMinute
     */
    public function setComments(ArrayCollection $comments) {
        $this->comments = $comments;
        return $this;
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'comments' => $this->comments,
            'agenda' => $this->agenda,
            'presenceList' => $this->presenceList,
            'items' => $this->items
        );
    }

}
