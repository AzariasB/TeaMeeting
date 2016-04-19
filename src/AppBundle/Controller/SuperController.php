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
 * The super controller is a helper,
 * a subset to the existing symfony controller.
 * It contains utils functions used in the controllers
 * of the project.
 *
 * @author boutina
 */
class SuperController extends Controller {

    /**
     * Get the current user
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

    /**
     * Remove an entity from the database
     * 
     * @param Object $entity
     */
    protected function removeEntity($entity) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
    }

    /**
     * Find the entity with the given
     * class name and the givne id
     * in the database
     * throw an error if not found
     * 
     * @param string $className
     * @param int $id
     * @return Object
     * @throws NotFoundHttpException
     */
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
    
    /**
     * Find the object with the given class name
     * the given predicate and the given ordering
     * 
     * @param string $className
     * @param array $predicate
     * @param array $ordering
     * @return Object
     */
    protected function getFromClass($className, $predicate, $ordering = []) {
        return $this->getDoctrine()
                        ->getRepository($className)
                        ->findBy($predicate, $ordering);
    }

    /**
     * Genereate the breadcrumb from the given entity,
     * the order of the breadcrumb is like this:
     * Project > Meeting > Meeting Minute > Minute Action
     * 
     * @param Object $entity
     * @return array
     */
    protected function generateBreadCrumbs($entity) {
        $arr1 = [];
        $text = $link = '';
        if ($entity instanceof \AppBundle\Entity\Project) {
            $link = $this->get('router')
                    ->generate('seeproject', ['projId' => $entity->getId()]
            );
            $text = 'Project';
        } else if ($entity instanceof \AppBundle\Entity\Meeting) {
            $arr1 = $this->generateBreadCrumbs($entity->getProject());
            $link = $this->get('router')
                    ->generate('see-meeting', ['meetingId' => $entity->getId()]
            );
            $text = 'Meeting';
        } else if ($entity instanceof \AppBundle\Entity\MeetingMinute) {
            $arr1 = $this->generateBreadCrumbs($entity->getMeeting());
            $link = $this->get('router')
                    ->generate('see-meeting-minute', ['meetingId' => $entity->getMeeting()->getId()]
            );
            $text = 'Meeting minute';
        } else if ($entity instanceof \AppBundle\Entity\ItemMinute) {
            $arr1 = $this->generateBreadCrumbs($entity->getMeetingMinute());
            $link = $this->get('router')
                    ->generate('see-minute-item', ['itemId' => $entity->getId()]);
            $text = 'Action';
        } else {
            return [];
        }
        return array_merge($arr1, [['text' => $text, 'link' => $link]]);
    }

    /**
     * If the assertion if false
     * throw an exception with the given message
     * 
     * @param boolean $assertion
     * @param string $errorMessage
     * @throws Exception
     */
    protected function assert($assertion, $errorMessage = 'An error occured') {
        if (!$assertion) {
            throw new Exception($errorMessage);
        }
    }
    
    protected function assertAdmin($errorMessage='Only the admin can have access to it'){
        $this->assert($this->isGranted('ROLE_ADMIN'),$errorMessage);
    }

}
