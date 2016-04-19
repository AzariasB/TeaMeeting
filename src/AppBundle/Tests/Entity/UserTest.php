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
 * Contains the class UserTest
 */

namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\User;
use AppBundle\Entity\Project;

/**
 * Description of UserTest
 *
 * @author boutina
 */
class UserTest extends WebTestCase {

    private function generateFullUser($admin = true) {
        $u = new User();
        $u->setIsAdmin($admin)
                ->setId(1)
                ->setPassword($admin ? 'admin' : 'user')
                ->setUsername($admin ? 'admin' : 'user');

        return $u;
    }

    public function testConstructor() {

        $admin = $this->generateFullUser();

        $this->assertNotNull($admin);
        $this->assertTrue($admin->getIsAdmin());
        $this->assertEmpty($admin->getProjects());
    }

    public function testProjects() {
        $user = $this->generateFullUser(false);

        $adding = new Project();

        $user->addProject($adding);
        $this->assertEquals($user->getProjects()->count(), 1);

        $user->removeProject(new Project());
        $this->assertEquals($user->getProjects()->count(), 1);

        $user->removeProject($adding);
        $this->assertEquals($user->getProjects()->count(), 0);
    }

    public function testSerialize() {
        $user = $this->generateFullUser(false);

        $id = $user->getId();
        $name = $user->getUsername();
        $admin = $user->getIsAdmin();
        
        $expected = array(
            'id' => $id,
            'name' => $name,
            'isAdmin' => $admin
        );
        
        $this->assertEquals($user->jsonSerialize(),$expected);
    }

}
