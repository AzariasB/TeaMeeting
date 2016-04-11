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
 * Contains the agenda ItemMinute
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of ItemMinute
 *
 * @author boutina
 * @ORM\Entity
 */
class ItemMinute implements \JsonSerializable {

    /**
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * The meeting relied to the item
     *
     * @ORM\ManyToOne(targetEntity="MeetingMinute",inversedBy="items")
     * @ORM\JoinColumn(name="meeting_id",referencedColumnName="id",onDelete="CASCADE")
     * @var MeetingMinute
     */
    private $meetingMinute;

    /**
     * @ORM\OneToOne(targetEntity="ItemAgenda")
     * @ORM\JoinColumn(name="item_agenda_id", referencedColumnName="id")
     * @var ItemAgenda
     */
    private $itemAgenda;

    /**
     * 
     * @ORM\Column(type="text",nullable=true)
     * @var string
     */
    private $comment;

    /**
     *
     * @ORM\Column(type="time",nullable=true)
     * @var DateTime
     */
    private $minutes;
    
    /**
     * @ORM\OneToMany(targetEntity="MinuteAction",mappedBy="itemMinute")
     * @var ArrayCollection
     */
    private $actions;

    /**
     *
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $postponed;

    public function __construct(MeetingMinute $meetMing = null, ItemAgenda $itemAgenda = null) {
        $this->actions = new ArrayCollection;
        $this->meetingMinute = $meetMing;
        $this->itemAgenda = $itemAgenda;
        $this->postponed = false;
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
     * Get meeting minute
     * 
     * @return MeetingMinute
     */
    public function getMeetingMinute() {
        return $this->meetingMinute;
    }

    /**
     * Get item agenda
     * 
     * @return ItemAgenda
     */
    public function getItemAgenda() {
        return $this->itemAgenda;
    }

    /**
     * Get comment
     * 
     * @return string
     */
    public function getComment() {
        return $this->comment;
    }

    /**
     * Get actions
     * 
     * @return ArrayCollection
     */
    public function getActions() {
        return $this->actions;
    }
    
    /**
     * Get minutes
     * 
     * @return Datetime
     */
    public function getMinutes(){
        return $this->minutes;
    }

    /**
     * Get postponed
     * 
     * @return boolean
     */
    public function getPostponed() {
        return $this->postponed;
    }

    /**
     * Set id
     * 
     * @param int $id
     * @return ItemMinute
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Set meeting minute
     * 
     * @param MeetingMinute $meetingMinute
     * @return ItemMinute
     */
    public function setMeetingMinute(MeetingMinute $meetingMinute) {
        $this->meetingMinute = $meetingMinute;
        return $this;
    }

    /**
     * Set itemAgenda
     * 
     * @param ItemAgenda $itemAgenda
     * @return ItemMinute
     */
    public function setItemAgenda(ItemAgenda $itemAgenda) {
        $this->itemAgenda = $itemAgenda;
        return $this;
    }

    /**
     * Set comment
     * 
     * @param string $comment
     * @return ItemMinute
     */
    public function setComment($comment) {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Set actions
     * 
     * @param ArrayCollection $actions
     * @return ItemMinute
     */
    public function setActions(ArrayCollection $actions) {
        $this->actions = $actions;
        return $this;
    }
    
    /**
     * Set minutes
     * 
     * @param \DateTime $minutes
     * @return ItemMinute
     */
    public function setMinutes(\DateTime $minutes){
        $this->minutes = $minutes;
        return $this;
    }

    /**
     * Set postponed
     * 
     * @param boolean $postponed
     * @return ItemMinute
     */
    public function setPostponed($postponed) {
        $this->postponed = $postponed;
        return $this;
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'agendaItem' => $this->itemAgenda,
            'minutes' => $this->minutes ? $this->minutes->getTimestamp()*1000 : 0,
            'comment' => $this->comment,
            'postponed' => $this->postponed,
            'actions' => $this->actions->toArray()
        );
    }

}
