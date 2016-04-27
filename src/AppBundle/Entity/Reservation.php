<?php
namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\BookableResourceInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Traits;

/**
 * Klasa wszystkich typów użytkowników systemu
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\ReservationRepository")
 * @ORM\Table(name="reservation", uniqueConstraints={@ORM\UniqueConstraint(name="resource_on_day", columns={"resource_id", "day"})})
 * @ORM\HasLifecycleCallbacks
 * 
 */
class Reservation
{
    use Traits\BaseEntityTrait;
    /**
     * Zarób (np. pokój), na który jest rezerwacaja
     * @ORM\ManyToOne(targetEntity="Resource", inversedBy="reservations")
     * @Assert\NotBlank()
     */
    protected $resource;
    
    /**
     * Dzień na który jest rezerwacja
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    protected $day;
    

    /**
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="reservation")
     */
    protected $payments;
    
    /**
     * Data wygasania rezerwacji
     * @ORM\Column(type="datetime")
     */
    protected $expires;
    
    /**
     * Cena
     * @ORM\Column(type="integer")
     */
    protected $price;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $createdByUser;

    public function __construct()
    {
    
        // Dodatkowy niepuliczny identyfikar obiektu/rekordu - używany jako dodatkowy np. przy kasowaniu
        $this->setRapas(substr(md5(rand(199, 9999)), 0, 10));
        $this->setExpires((new \DateTime())->add(new \DateInterval('P2D')));
    }   

    /**
     * Set day
     *
     * @param \DateTime $day
     *
     * @return Reservation
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return \DateTime
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set expires
     *
     * @param \DateTime $expires
     *
     * @return Reservation
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Get expires
     *
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Reservation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Reservation
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Reservation
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set resource
     *
     * @param \AppBundle\Entity\Resource $resource
     *
     * @return Reservation
     */
    public function setResource(\AppBundle\Entity\Resource $resource = null)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get resource
     *
     * @return \AppBundle\Entity\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set rapas
     *
     * @param string $rapas
     *
     * @return Reservation
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
     * Set createdByUser
     *
     * @param \AppBundle\Entity\User $createdByUser
     *
     * @return Reservation
     */
    public function setCreatedByUser(\AppBundle\Entity\User $createdByUser = null)
    {
        $this->createdByUser = $createdByUser;

        return $this;
    }

    /**
     * Get createdByUser
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreatedByUser()
    {
        return $this->createdByUser;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Reservation
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

    /**
     * Add payment
     *
     * @param \AppBundle\Entity\Payment $payment
     *
     * @return Reservation
     */
    public function addPayment(\AppBundle\Entity\Payment $payment)
    {
        $this->payments[] = $payment;

        return $this;
    }

    /**
     * Remove payment
     *
     * @param \AppBundle\Entity\Payment $payment
     */
    public function removePayment(\AppBundle\Entity\Payment $payment)
    {
        $this->payments->removeElement($payment);
    }

    public function getPaidAmount()
    {
        $amount=0;
        foreach($this->payments as $onepayment)
        {
            $amount+=$onepayment->getAmount();
        }
        return $amount;
    }
    
    /**
     * @return bool
     */
    public function getPaid()
    {
        return $this->price <= $this->getPaidAmount();
    }
    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayments()
    {
        return $this->payments;
    }
}
