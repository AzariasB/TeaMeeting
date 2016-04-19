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
 * Contains the class MinuteAction
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * The minute action is an action that must be 
 * done by one member of the projectit contains 
 * a description, an implementer a stae
 * and a deadline.
 *
 * @author boutina
 * @ORM\Entity
 */
class MinuteAction implements \JsonSerializable {

    const ACTION_IN_PROGRESS = 0;
    const ACTION_LATE = 1;
    const ACTION_WORK_UNDER_REVIEW = 2;
    const ACTION_COMPLETE = 3;
    const ACTION_NO_LONGER_REQUIRED = 4;

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
     * @ORM\ManyToOne(targetEntity="ItemMinute",inversedBy="actions")
     * @ORM\JoinColumn(name="item_id",referencedColumnName="id",onDelete="CASCADE")
     * @var ItemMinute
     */
    private $itemMinute;

    /**
     *
     * State of the item in the item minute
     * default is in progress
     * 
     * @ORM\Column(type="smallint")
     * @var int
     */
    private $state;

    /**
     *
     * Description of the action
     * 
     * @ORM\Column(type="text")
     * @var string
     */
    private $description;

    /**
     * Implementer
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="implementer_id",referencedColumnName="id")
     * @var User
     */
    private $implementer;

    /**
     * Date when the action must be finished
     * 
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $deadline;

    public function __construct() {
        $this->state = self::ACTION_IN_PROGRESS;
    }

    /**
     * Get item minute
     * 
     * @return ItemMinute
     */
    public function getItemMinute() {
        return $this->itemMinute;
    }

    /**
     * Set item minute
     * 
     * @param ItemMinute $minutes
     * @return MinuteAction
     */
    public function setItemMinute(ItemMinute $minutes) {
        $this->itemMinute = $minutes;
        return $this;
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
     * Get state
     * 
     * @return int
     */
    public function getState() {
        return $this->state;
    }

    /**
     * Get description
     * 
     * @return int
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Get implementer
     * 
     * @return User
     */
    public function getImplementer() {
        return $this->implementer;
    }

    /**
     * Get deadline
     * 
     * @return DateTime
     */
    public function getDeadline() {
        return $this->deadline;
    }

    /**
     * Set id
     * 
     * @param int $id
     * @return MinuteAction
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Set state
     * 
     * @param int $state
     * @return MinuteAction
     */
    public function setState($state) {
        $this->state = $state;
        return $this;
    }

    /**
     * Set description
     * 
     * @param string $description
     * @return MinuteAction
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * Set implementer
     * 
     * @param User $implementer
     * @return MinuteAction
     */
    public function setImplementer(User $implementer) {
        $this->implementer = $implementer;
        return $this;
    }

    /**
     * Set deadline
     * 
     * @param DateTime $deadline
     * @return MinuteAction
     */
    public function setDeadline(\DateTime $deadline) {
        $this->deadline = $deadline;
        return $this;
    }

    /**
     * Returns wether the deadline of the
     * action is passed
     * 
     * @return boolean
     */
    public function isOutdated() {
        return $this->deadline < new \DateTime;
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'state' => $this->state,
            'description' => $this->description,
            'deadline' => $this->deadline->getTimestamp() * 1000,
            'implementer' => $this->implementer
        );
    }

}
