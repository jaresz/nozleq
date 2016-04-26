<?php
namespace AppBundle\Model;

use AppBundle\Entity\User;
use AppBundle\Entity\Resource;
use AppBundle\Entity\Reservation;
use Doctrine\Common\Persistence\ObjectManager;

class ReservationManager extends AbstractClassWithEntityManager {
    
    
    public function getReservation(Resource $resouce, \DateTime $reservarionDay)
    {
        $em = $this->entityManager;
        $rez = $em->getRepository('AppBundle:Reservation')->getReservation($resouce, $reservarionDay);
        return $rez;
    }
    
    public function makeReservation(Resource $resouce, \DateTime $reservarionDay, User $maker, $reservationName)
    {
        if ($this->getReservation($resouce, $reservarionDay)) return false;
        
        $newReservation = new Reservation();
        
        $em = $this->entityManager;
        $em->transactional(function($em) use ($resouce, $reservarionDay, $maker, $reservationName, $newReservation) {
            //$fields = $em->getRepository('AppBundle:Reservation')->findAll();
            //$newReservation = new Reservation();
            $newReservation->setResource($resouce);
            $newReservation->setDay($reservarionDay);
            $newReservation->setName($reservationName);
            $newReservation->setCreatedByUser($maker);
            $em->persist($newReservation);
            $em->flush();
        });
        return  $newReservation;
    }
    
}