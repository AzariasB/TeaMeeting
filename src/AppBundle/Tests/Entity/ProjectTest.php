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
 * Contains the test ProjectTest
 */

namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;

/**
 * Test the project entity
 *
 * @author boutina
 */
class ProjectTest extends WebTestCase {

    private function generateFullProject() {
        $p = new Project();
        $p->setLeader(new User);
        $p->setSecretary(new User);
        $p->setLocked(false);

        return $p;
    }

    public function testConstructor() {
        $p = $this->generateFullProject();

        $this->assertNotNull($p);
        $this->assertNotNull($p->getSecretary());
        $this->assertNotNull($p->getLeader());
        $this->assertEmpty($p->getMeetings());
        $this->assertEmpty($p->getParticipants());
        $this->assertEmpty($p->getRoles());
    }

    public function testArrayOperations() {


        $test = array(
            'Meeting' => Meeting::class,
            'Participant' => User::class,
            'Role' => UserRole::class
        );

        foreach ($test as $name => $class) {
            $this->operations($name, $class);
        }
    }

    private function operations($objName, $className) {
        $p = $this->generateFullProject();

        $theObj = new $className;

        $addMethod = 'add' . $objName;
        $removeMethod = 'remove' . $objName;
        $getMethod = 'get' . $objName . 's';

        $p->$addMethod($theObj);

        $this->assertEquals($p->$getMethod()->count(), 1);

        if (is_callable(array($p, $removeMethod))) {
            $otherObj = new $className;
            call_user_func_array(array($p,$removeMethod), array($otherObj));
            $this->assertEquals($p->$getMethod()->count(), 1);
            
            call_user_func_array(array($p,$removeMethod), array($theObj));
            $this->assertEquals($p->$getMethod()->count(), 0);
        }
    }

}
