<?php
namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\BookableResourceInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Traits;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Klasa filtrująca wolne pokoje
 * 
 */
class VacancyFilter
{
    
    /**
     * Dzień rezerwacji
     * @var Date
     */
    public $day;
    
    /**
     * Maksymalna liczba osób, jaka może nocować w pokoju
     * @var integer
     */
    public  $capacity;
    
    public function __construct()
    {
       $this->day = (new \DateTime())->add(new \DateInterval('P7D'));
    }
    
}
