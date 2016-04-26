<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Traits;


/**
 * Klasa wszystkich typów użytkowników systemu
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\RoomRepository")
 * @ORM\Table(name="room")
 * @ORM\HasLifecycleCallbacks
 */
class Room extends Resource
{
    
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $price;
    
    /**
     * * @ORM\Column(type="text", nullable=true)
     */
    protected $description;
    
    /**
     * Maksymalna liczba osób, jaka może nocować w pokoju
     * @var integer
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $capacity;
    
    /**
     * Czy pokój ma minibar?
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $minibar;
    
    /**
     * Czy pokój ma klimatyzację
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $airConditioned;


    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $createdByUser;
    

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Room
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set capacity
     *
     * @param integer $capacity
     *
     * @return Room
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * Get capacity
     *
     * @return integer
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set minibar
     *
     * @param boolean $minibar
     *
     * @return Room
     */
    public function setMinibar($minibar)
    {
        $this->minibar = $minibar;

        return $this;
    }

    /**
     * Get minibar
     *
     * @return boolean
     */
    public function getMinibar()
    {
        return $this->minibar;
    }

    /**
     * Set airConditioned
     *
     * @param boolean $airConditioned
     *
     * @return Room
     */
    public function setAirConditioned($airConditioned)
    {
        $this->airConditioned = $airConditioned;

        return $this;
    }

    /**
     * Get airConditioned
     *
     * @return boolean
     */
    public function getAirConditioned()
    {
        return $this->airConditioned;
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
     * @return Room
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
     * @return Room
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
     * @return Room
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
     * Add reservation
     *
     * @param \AppBundle\Entity\Reservation $reservation
     *
     * @return Room
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
     * Set createdByUser
     *
     * @param \AppBundle\Entity\User $createdByUser
     *
     * @return Room
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
     * Set rapas
     *
     * @param string $rapas
     *
     * @return Room
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
     * @return Room
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
