<?php
namespace AppBundle\Model;

use AppBundle\Entity\User;
use AppBundle\Entity\Resource;
use AppBundle\Entity\Reservation;
use Doctrine\Common\Persistence\ObjectManager;

class ReservationManager extends AbstractClassWithEntityManager
{

    public function getReservation(Resource $resouce, \DateTime $reservarionDay)
    {
        $em = $this->entityManager;
        $rez = $em->getRepository('AppBundle:Reservation')->getReservation($resouce, $reservarionDay);
        return $rez;
    }

    /**
     * Tworzy rezerwacje na pokój hotelowy, miotłę, rower, stolik, czy cokolwiek innego co oferuje hotel.
     * W razie powodzenie zwraca rezerwację,
     * w razie porażki zwraca nic.
     * 
     * @param Resource $resouce            
     * @param \DateTime $reservarionDay            
     * @param User $maker            
     * @param unknown $reservationName            
     */
    public function makeReservation(Resource $resouce, \DateTime $reservarionDay, User $maker, $reservationName)
    {
        if ($this->getReservation($resouce, $reservarionDay))
            return false;
        
        $newReservation = new Reservation();
        
        $em = $this->entityManager;
        /*
         * Operacja rezerwacji jest wykonana w transakcji,
         * istnieje bowiem ryzyko, że ktoś nas ubiegnie z rezerwacją.
         * Tak, pojedynczas operacja na bazie w transakcji, to pewna przesada.
         * Wychodzi na to samo co bez transakcji i ze sprwadzeniem efektu.
         * Można jednak tą transakcje łatwo rozbudować.
         */
        $em->transactional(function ($em) use ($resouce, $reservarionDay, $maker, $reservationName, $newReservation) {
            $newReservation->setResource($resouce);
            $newReservation->setDay($reservarionDay);
            $newReservation->setName($reservationName);
            $newReservation->setCreatedByUser($maker);
            $em->persist($newReservation);
            $em->flush();
        });
        return $newReservation;
    }
}