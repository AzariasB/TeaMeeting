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
 * Contains the class UserAnswer
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\Project;

/**
 * Description of UserAnswer
 *
 * 
 * @author boutina
 * @ORM\Entity
 */
class UserAnswer implements \JsonSerializable {

    const ANSWER_NO = 1;
    const ANSWER_MAYBE = 2;
    const ANSWER_YES = 3;
    const NO_ANSWER = 4;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Meeting",inversedBy="answers")
     * @ORM\JoinColumn(name="meeting", referencedColumnName="id",nullable=false)
     * 
     * @var Meeting
     */
    private $meeting;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @var User
     */
    private $user;

    /**
     * Answer given by the user
     * default is no
     * 
     * @ORM\Column(type="smallint")
     *
     * @var int
     */
    private $answer;

    public function __construct() {
        $this->answer = self::NO_ANSWER;
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
     * @return UserAnswer
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Get meeting
     * 
     * @return Meeting
     */
    public function getMeeting() {
        return $this->meeting;
    }

    /**
     * Set meeting
     * 
     * @param Meeting $meet
     * @return UserAnswer
     */
    public function setMeeting(Meeting $meet) {
        $this->meeting = $meet;
        return $this;
    }

    /**
     * Get User
     * 
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set user
     * 
     * @param User $user
     * @return UserAnswer
     */
    public function setUser(User $user) {
        $this->user = $user;
        return $this;
    }

    /**
     * Get answer
     * 
     * @return int
     */
    public function getAnswer() {
        return $this->answer;
    }

    /**
     * Set answer
     * 
     * This setter is particular.
     * Since it does not always change the value of the answer.
     * Here are the rules : the user is changed only from :
     * NO to MAYBE and MAYBE to NO
     * (MAYBE,NO) to YES
     * 
     * once it's on YES, it cannot be changed
     * 
     * @param int $nwAnswer
     * @return boolean true if the value was changed, false otherwise
     */
    public function setAnswer($nwAnswer) {
        if ($this->answer === self::NO_ANSWER) {
            $this->answer = $nwAnswer;
            return true;
        } else {
            if ($this->answer === self::ANSWER_NO || $this->answer === self::ANSWER_MAYBE ||
                    $this->answer === self::ANSWER_YES && $nwAnswer === self::ANSWER_YES) {
                $this->answer = $nwAnswer;
                return true;
            }
            return false;
        }
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'meeting' => $this->meeting,
            'user' => $this->user,
            'answer' => $this->answer
        );
    }

    public function answerString() {
        switch ($this->answer) {
            case self::ANSWER_NO:
                return 'No';
            case self::ANSWER_YES :
                return 'Yes';
            case self::ANSWER_MAYBE :
                return 'Maybe';
            default :
                return 'Not answered';
        }
    }

}
