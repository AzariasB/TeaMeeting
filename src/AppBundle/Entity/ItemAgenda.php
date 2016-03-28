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
 * Contains the entity ItemAgenda
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemAgenda is the item of and agenda
 *
 * @author boutina
 * @ORM\Entity
 */
class ItemAgenda implements \JsonSerializable {

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
     * @ORM\ManyToOne(targetEntity="Agenda",inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     * @var Agenda
     */
    private $agenda;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="proposer_id",referencedColumnName="id")
     * 
     * @var User
     */
    private $proposer;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    private $title;


    /**
     * Index of an item in its agenda
     *
     * @ORM\Column(type="integer")
     * @var int 
     */
    private $position;

    public function __construct(Agenda $ag = null, User $proposer = null, $title = null) {
        $this->agenda = $ag;
        $this->proposer = $proposer;
        $this->title = $title;
        $this->position = -1;
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
     * @return ItemAgenda
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * 
     * @return Agenda
     */
    public function getAgenda() {
        return $this->agenda;
    }

    /**
     * Set agenda
     * 
     * @param Agenda $ag
     */
    public function setAgenda(Agenda $ag) {
        $this->agenda = $ag;
    }

    /**
     * Get proposer
     * 
     * @return User
     */
    public function getProposer() {
        return $this->proposer;
    }

    /**
     * Setproposer
     * 
     * @param User $prop
     * @return ItemAgenda
     */
    public function setProposer(User $prop) {
        $this->proposer = $prop;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    /**
     * Set title
     * 
     * @param string $tit
     * @return ItemAgenda
     */
    public function setTitle($tit) {
        $this->title = $tit;
        return $this;
    }
    
    /**
     * Get position
     * 
     * @return int
     */
    public function getPosition(){
        return $this->position;
    }
    
    /**
     * 
     * @param int $position
     * @return ItemAgenda
     */
    public function setPosition($position){
        $this->position = $position;
        return $this;
    }

    /**
     * Json serialize
     * 
     * @return array
     */
    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'proposer' => $this->proposer,
            'title' => $this->title,
            'position' => $this->position
        );
    }

}
