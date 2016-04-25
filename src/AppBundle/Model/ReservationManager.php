<?php
namespace AppBundle\Model;

use AppBundle\Entity\User;
use AppBundle\Entity\Resource;
use AppBundle\Entity\Reservation;
use Doctrine\Common\Persistence\ObjectManager;

class ReservationManager extends AbstractClassWithEntityManager {
    
    
    public function findUserByName($username)
    {
        $em = $this->entityManager;
        $fuser = $em->getRepository('AppBundle:User')->findOneByName($username);
        return $fuser;
    }
    
    public function findUserByOfficeUserName($username)
    {
        $em = $this->entityManager;
        $fuser = $em->getRepository('AppBundle:User')->findOneByOfficeUserName($username);
        return $fuser;
    }
    
}