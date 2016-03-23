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
 * Contains the class UserRoleTest
 */

namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


use AppBundle\Entity\UserRole;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;

/**
 * Testing the UserRoleEntity
 *
 * @author boutina
 */
class UserRoleTest extends WebTestCase {

    private function generateFullRole() {
        $rol = new UserRole();

        $rol->setId(1)
                ->setProject(new Project())
                ->setStudent(new User())
                ->setRoleName('role');

        return $rol;
    }

    public function testConstructor() {
        $r = $this->generateFullRole();

        $this->assertNotNull($r);

        $this->assertEquals($r->getRoleName(), 'role');
        $this->assertEquals(1, $r->getId());
        $this->assertNotNull($r->getStudent());
        $this->assertNotNull($r->getProject());
    }
    
    public function testSerialize(){
        $r = $this->generateFullRole();
        
        $id = $r->getId();
        $name = $r->getRoleName();
        $user = $r->getStudent();
        $proj = $r->getProject();
        
        $expected = array(
          'id' => $id,
            'name' => $name,
            'student' => $user,
            'project' => $proj
        );
        
        $this->assertEquals($expected,$r->jsonSerialize());
    }

}
