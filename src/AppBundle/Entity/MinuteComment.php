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
 * Contains the class MinuteComment
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of MinuteComment
 *
 * @author boutina
 * @ORM\Entity
 */
class MinuteComment implements \JsonSerializable {

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
     * @ORM\ManyToOne(targetEntity="MeetingMinute",inversedBy="comments")
     * @ORM\JoinColumn(name="meeting_minute_id",referencedColumnName="id")
     * @var MeetingMinute
     */
    private $meetingMinute;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="commenter_id",referencedColumnName="id")
     * @var User
     */
    private $commenter;

    /**
     *
     * @ORM\Column(type="text")
     * @var string 
     */
    private $comment;

    /**
     *
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $date;

    /**
     * 
     * @param MeetingMinute $minute
     * @param User $commenter
     * @param string $comment
     */
    public function __construct(MeetingMinute $minute = null, User $commenter = null, $comment = '') {
        $this->date = new \DateTime;
        $this->meetingMinute = $minute;
        $this->commenter = $commenter;
        $this->comment = $comment;
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
     * Get commenter
     * 
     * @return User
     */
    public function getCommenter() {
        return $this->commenter;
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
     * Get datetime
     * 
     * @return DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set id
     * 
     * @param int $id
     * @return MinuteComment
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Set meeting minute
     * 
     * @param MeetingMinute $meetingMinute
     * @return MinuteComment
     */
    public function setMeetingMinute(MeetingMinute $meetingMinute) {
        $this->meetingMinute = $meetingMinute;
        return $this;
    }

    /**
     * Set commnter
     * 
     * @param User $commenter
     * @return MinuteComment
     */
    public function setCommenter(User $commenter) {
        $this->commenter = $commenter;
        return $this;
    }

    /**
     * Set comment
     * 
     * @param string $comment
     * @return MinuteComment
     */
    public function setComment($comment) {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Set date
     * 
     * @param DateTime $date
     * @return MinuteComment
     */
    public function setDate(\DateTime $date) {
        $this->date = $date;
        return $this;
    }

    public function getPassedString($full = false) {
        $today = new \DateTime;
        $diff = $today->diff($this->date);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = [
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second'
        ];

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
        if (!$full) {
            $string = array_slice($string, 0, 1);
        }
        return $string ? implode(',', $string) . ' ago' : 'just now';
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'commenter' => $this->commenter,
            'comment' => $this->comment,
            'date' => $this->date->getTimestamp() * 1000,
            'ago' => $this->getPassedString()
        );
    }

}
