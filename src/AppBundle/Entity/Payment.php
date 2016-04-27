<?php
namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\BookablePaymentInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Traits;
use Symfony\Component\Config\Definition\BooleanNode;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Klasa wszystkich typów użytkowników systemu
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PaymentRepository")
 *
 * @ORM\Table(name="Payment")
 * @ORM\HasLifecycleCallbacks
 */
class Payment
{
    use Traits\BaseEntityTrait;

    /**
     * Rezerwacja
     * @ORM\ManyToOne(targetEntity="Reservation", inversedBy="payments")
     * @Assert\NotBlank()
     */
    protected $reservation;

    /**
     * @ORM\Column(type="integer")
     */
    protected $amount;
   
    public function __construct()
    {
        $this->reservations = new \Doctrine\Common\Collections\ArrayCollection();
        // ustawianie dodatkowego niepulicznego identyfikara obiektu/rekordu - używany jako dodatkowy np. przy kasowaniu
        $this->setRapas(substr(md5(rand(199, 9999)), 0, 20));
    }

    /**
     * Add reservation
     *
     * @param \AppBundle\Entity\Reservation $reservation
     *
     * @return Payment
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
     * @return Payment
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
     * Set reservations
     *
     * @param \AppBundle\Entity\Reservation $reservations
     *
     * @return Payment
     */
    public function setReservations(\AppBundle\Entity\Reservation $reservations = null)
    {
        $this->reservations = $reservations;

        return $this;
    }

    /**
     * Set reservation
     *
     * @param \AppBundle\Entity\Reservation $reservation
     *
     * @return Payment
     */
    public function setReservation(\AppBundle\Entity\Reservation $reservation = null)
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get reservation
     *
     * @return \AppBundle\Entity\Reservation
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }
}
