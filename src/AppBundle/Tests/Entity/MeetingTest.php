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
 * Contains the test MeetingTest
 */

namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\Project;

/**
 * Testing the meeting entity
 *
 * @author boutina
 */
class MeetingTest extends WebTestCase {

    private function generateFullMeeting() {
        $meeting = new Meeting();

        $meeting->setId(rand(0, 100));
        $meeting->setDate(new \DateTime());
        $meeting->setRoom(base64_encode(random_bytes(6)));
        $meeting->setProject(new Project());

        return $meeting;
    }

    public function testConstructor() {
        $meeting = new Meeting();

        $this->assertNotNull($meeting);
    }

    public function testSerialize() {
        $meeting = $this->generateFullMeeting();

        $id = $meeting->getId();
        $date = $meeting->getDate();
        $room = $meeting->getRoom();
        $proj = $meeting->getProject();

        $res = array(
            'id' => $id,
            'date' => $date,
            'room' => $room,
            'project' => $proj
        );
        
        $this->assertEquals($res,$meeting->jsonSerialize());
    }

}
