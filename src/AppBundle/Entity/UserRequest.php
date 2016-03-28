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
 * Contains the class UserRequest
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of UserRequest
 *
 * @author boutina
 * @ORM\Entity
 */
class UserRequest implements \JsonSerializable {

    const STATE_PENDING = 1;
    const STATE_NOTED_NO_CHANGE = 2;
    const STATE_NOTED_ON_AGENDA = 3;
    const STATE_AGREED = 4;

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
     * State of the item in the agenda
     * default is pending
     * 
     * @ORM\Column(type="smallint")
     * @var int
     */
    private $state;

    /**
     * The agenda to which the request is made
     *
     * @ORM\ManyToOne(targetEntity="Agenda")
     * @ORM\JoinColumn(name="agenda_id",referencedColumnName="id")
     * @var Agenda
     */
    private $agenda;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="sender_id",referencedColumnName="id")
     * @var User
     */
    private $sender;

    /**
     * Date when the user send the request
     * the default value is the current date
     * 
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="ItemAgenda")
     * @ORM\JoinColumn(nullable=true,name="item_id",referencedColumnName="id")
     * @var ItemAgenda
     */
    private $item;

    public function __construct($state = self::STATE_PENDING) {
        $this->date = new \DateTime;
        $this->state = $state;
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
     * Get agenda
     * 
     * @return Agenda
     */
    public function getAgenda() {
        return $this->agenda;
    }

    /**
     * Get sender
     * 
     * @return User
     */
    public function getSender() {
        return $this->sender;
    }

    /**
     * Set id
     * 
     * @param int $id
     * @return UserRequest
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Set state
     * 
     * @param int $state
     * @return UserRequest
     */
    public function setState($state) {
        $this->state = $state;
        return $this;
    }

    /**
     * Set agenda
     * 
     * @param Agenda $agenda
     * @return UserRequest
     */
    public function setAgenda(Agenda $agenda) {
        $this->agenda = $agenda;
        return $this;
    }

    /**
     * Set sender
     * 
     * @param User $sender
     * @return UserRequest
     */
    public function setSender(User $sender) {
        $this->sender = $sender;
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
     * @param \DateTime $date
     * @return UserRequest
     */
    public function setDate(\DateTime $date) {
        $this->date = $date;
        return $this;
    }

    /**
     * Get item
     * 
     * @return ItemAgenda
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * Set item
     * 
     * @param ItemAgenda $item
     * @return UserRequest
     */
    public function setItem(ItemAgenda $item) {
        $this->item = $item;
        return $this;
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'state' => $this->state,
            'stateString' => $this->stateToString(),
            'sender' => $this->sender,
            'date' => $this->date,
            'item' => $this->item
        );
    }

    /**
     * Current state to string
     * 
     * @return string
     */
    public function stateToString() {
        switch ($this->state) {
            case self::STATE_AGREED:
                return 'Agreed';
            case self::STATE_NOTED_NO_CHANGE:
                return 'Noted, not changed in the agenda';
            case self::STATE_NOTED_ON_AGENDA:
                return 'Noted, changed on the agenda';
            case self::STATE_PENDING:
                return 'Pending';
        }
    }

    public function isPending() {
        return $this->state === self::STATE_PENDING;
    }

    public function isAgreed() {
        return $this->state === self::STATE_AGREED;
    }

    public function isNotedInAgenda() {
        return $this->state === self::STATE_NOTED_ON_AGENDA;
    }

    public function isNotInAgenda() {
        return $this->state === self::STATE_NOTED_NO_CHANGE;
    }

}
