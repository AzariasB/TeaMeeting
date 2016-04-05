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
 * Contains the class UserPresence
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of UserPresence
 *
 * @author boutina
 * @ORM\Entity
 */
class UserPresence implements \JsonSerializable {

    //put your code here

    const PRESENT_FOR_WHOLE_MEETING = 0;
    const PRESENT_ARRIVED_LATE = 0x1;
    const PRESENT_LEFT_EARLY = 0x2;
    const ABSENT_NO_APOLOGIES = 0x4;
    const ABSENT_APOLOGIES_RECEIVED_BEFORE_MEETING = 0x8;
    const ABSENT_APOLOGIES_RECEIVED_AFTER_MEETING = 0xA;

    /**
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * The meeting relied to the presence
     *
     * @ORM\ManyToOne(targetEntity="MeetingMinute",inversedBy="presentUsers")
     * @ORM\JoinColumn(name="meeting_id",referencedColumnName="id")
     * @var MeetingMinute
     */
    private $meetingMinute;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     * @var User
     */
    private $user;

    /**
     *
     * State of the item in the agenda
     * default is pending
     * 
     * @ORM\Column(type="smallint")
     * @var int
     */
    private $state;

    public function __construct() {
        $this->state = self::ABSENT_NO_APOLOGIES;
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
     * Get user
     * 
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Get state
     * 
     * @return int
     */
    public function getState() {
        return $this->state;
    }

    /**
     * Set id
     * 
     * @param int $id
     * @return UserPresence
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Set meeting minute
     * 
     * @param MeetingMinute $meetingMinute
     * @return UserPresence
     */
    public function setMeetingMinute(MeetingMinute $meetingMinute) {
        $this->meetingMinute = $meetingMinute;
        return $this;
    }

    /**
     * Set user
     * 
     * @param User $user
     * @return UserPresence
     */
    public function setUser(User $user) {
        $this->user = $user;
        return $this;
    }

    public function wasAbsent() {
        return $this->state >= self::ABSENT_NO_APOLOGIES;
    }

    public function wasPresent() {
        return $this->state < self::ABSENT_NO_APOLOGIES;
    }

    /**
     * Set state
     * 
     * @param int $state
     * @return UserPresence
     */
    public function setState($state) {
        $this->state = $state;
        return $this;
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'state' => $this->state,
            'user' => $this->user
        );
    }

}
