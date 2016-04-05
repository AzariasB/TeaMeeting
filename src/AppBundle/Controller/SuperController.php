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
 * Contains the class SuperController
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of SuperController
 *
 * @author boutina
 */
class SuperController extends Controller {

    /**
     * 
     * @return User
     */
    protected function getCurrentUser() {
        return $this->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * Save entity in the database
     * 
     * @param Object $entity
     */
    protected function saveEntity(&$entity) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();
    }

    protected function removeEntity($entity) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
    }

    protected function getEntityFromId($className, $id) {
        $entity = $this->getDoctrine()->getManager()->find($className, $id);
        if (!$entity) {
            throw $this->createNotFoundException('Entity not found');
        }
        return $entity;
    }

    /**
     * Find all the entity
     * of a given class
     * 
     * @param string $className
     * @return ArrayCollection
     */
    protected function getAllFromClass($className) {
        return $this->getDoctrine()->getRepository($className)->findAll();
    }

    protected function getFromClass($className, $predicate) {
        return $this->getDoctrine()
                        ->getRepository($className)
                        ->findBy($predicate);
    }

}
