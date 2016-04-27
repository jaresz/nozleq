<?php
namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\BookableResourceInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Traits;
use Symfony\Component\Config\Definition\BooleanNode;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Klasa wszystkich typów użytkowników systemu
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\ResourceRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\Table(name="resource")
 * @ORM\HasLifecycleCallbacks
 */
class Resource
{
    use Traits\BaseEntityTrait;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $price;
    
    /**
     * @ORM\OneToMany(targetEntity="Reservation", mappedBy="resource")
     */
    protected $reservations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reservations = new \Doctrine\Common\Collections\ArrayCollection();
        // ustawianie dodatkowego niepulicznego identyfikara obiektu/rekordu - używany jako dodatkowy np. przy kasowaniu
        $this->setRapas(substr(md5(rand(1200, 9990)), 0, 20));
    }

    /**
     * Add reservation
     *
     * @param \AppBundle\Entity\Reservation $reservation
     *
     * @return Resource
     */
    public function addReservation(\AppBundle\Entity\Reservation $reservation)
    {
        $this->reservations[] = $reservation;

        return $this;
    }

    /**
     * Remove reservation
     *
     * @param \AppBundle\Entity\Reservation $reservation
     */
    public function removeReservation(\AppBundle\Entity\Reservation $reservation)
    {
        $this->reservations->removeElement($reservation);
    }

    /**
     * Get reservations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReservations()
    {
        return $this->reservations;
    }

    /**
     * Set rapas
     *
     * @param string $rapas
     *
     * @return Resource
     */
    public function setRapas($rapas)
    {
        $this->rapas = $rapas;

        return $this;
    }

    /**
     * Get rapas
     *
     * @return string
     */
    public function getRapas()
    {
        return $this->rapas;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Resource
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }
}
