<?php
namespace AppBundle\Model;

use AppBundle\Entity\User;
use AppBundle\Entity\Resource;
use AppBundle\Entity\Reservation;
use Doctrine\Common\Persistence\ObjectManager;

class ReservationManager extends AbstractClassWithEntityManager {
    
    
    public function getReservation(Resource $resouce, $reservarionDay)
    {
        $em = $this->entityManager;
        $rez = $em->getRepository('AppBundle:Reservation')->getReservation($resouce, $reservarionDay);
        return $rez;
    }
    
}