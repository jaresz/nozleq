<?php
namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Resource;

/**
 * ReservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReservationRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Zwraca rezerwacje zasobu w danym dniu
     * @param Resource $resouce
     * @param unknown $reservarionDay
     */
    public function getReservation(Resource $resouce, $reservarionDay)
    {
        
        $qb = $this->createQueryBuilder('b')
            ->select('b')
            ->where("b.resource = :res")
            ->andWhere("b.day = :day")
            ->setParameter('res', $resouce)
            ->setParameter('day', $reservarionDay);
        
        $qb->setMaxResults(1);
        
        $query = $qb->getQuery();
        try {
            $ten = $query->getSingleResult();
        } catch (\Doctrine\Orm\NoResultException $e) {
            $ten = null;
        }
        
        return $ten;
    }
 
    
}
