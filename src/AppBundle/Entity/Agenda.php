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
 * Contains the entity Agenda
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * The agenda is what is created for each meeting.
 * A meeting contains a single 'true' agenda,
 * and severals draft agendas
 *
 * @author boutina
 * @ORM\Entity
 */
class Agenda implements \JsonSerializable {

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
     * @ORM\ManyToOne(targetEntity="Meeting",inversedBy="agendas")
     * @ORM\JoinColumn(nullable=false)
     * @var Meeting
     */
    private $meeting;

    /**
     * 
     * @ORM\OneToMany(targetEntity="ItemAgenda",mappedBy="agenda",cascade={"persist","remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     * @var ArrayCollection 
     */
    private $items;

    public function __construct(Meeting &$meet) {
        $this->items = new ArrayCollection;
        $this->meeting = $meet;
        $meet->addAgenda($this);
        $this->createBaseItems();
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
     * @return Agenda
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
     * @param Meeting $met
     * @return Agenda
     */
    public function setMeeting(Meeting $met) {
        $this->meeting = $met;
        return $this;
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
     * Set items
     * 
     * @param ArrayCollection $items
     * @return Agenda
     */
    public function setItems(ArrayCollection $items) {
        $this->items = $items;
        return $this;
    }

    public function addItem(ItemAgenda $it) {
        $it->setIndex($this->items->count());
        $this->items->add($it);
        return $this;
    }

    /**
     * Create the three basic items of an agenda :
     * 1. missing excuses
     * 2. agenda (order paper)
     * 3. the 'minute action'
     * 
     * return false is the meeting was not initialized
     * @return boolean
     */
    public function createBaseItems() {
        if (!$this->meeting) {
            return false;
        } else {
            $pre = $this->meeting->getChairMan();
            $item1 = new ItemAgenda($this, $pre, 'Missing excuses', ItemAgenda::STATE_AGREED);
            $item2 = new ItemAgenda($this, $pre, 'Agenda', ItemAgenda::STATE_AGREED);
            $item3 = new ItemAgenda($this, $pre, 'Minute action', ItemAgenda::STATE_AGREED);
            $this
                    ->addItem($item1)
                    ->addItem($item2)
                    ->addItem($item3);
            return true;
        }
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'items' => $this->items,
        );
    }

//put your code here
}
