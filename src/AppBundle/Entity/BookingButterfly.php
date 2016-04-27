<?php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints\Date;

/**
 * Klasa chwilowego obiektu, występującego w trakcie tworzenia rezerwacji (przed dokonaniem rezerwacji)
 */
class BookingButterfly
{

    /**
     * Rezerwowany zasób
     * 
     * 
     */
    protected $resourceId;

    /**
     * Dzień rezerwacji
     * 
     * @var Date
     */
    protected $day;

    /**
     * Nazwa rezerwacji - zazwyczaj nazwisko lub pseudonim Gościa
     * 
     * @var String
     */
    protected $name;

    public function __construct($resourceId, $day, $name)
    {
        $this->setResourceId($resourceId);
        $this->setDay($day);
        $this->setName($name);
    }
    
    /**
     * Set resourceId
     *
     * @param \AppBundle\Entity\ResourceId $resourceId            
     *
     * @return BookingButterfly
     */
    public function setResourceId($resourceId = null)
    {
        $this->resourceId = $resourceId;
        
        return $this;
    }

    /**
     * Get resourceId
     *
     * @return \AppBundle\Entity\ResourceId
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * Set day
     *
     * @param \DateTime $day            
     *
     * @return BookingButterfly
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
     * Set name
     *
     * @param
     *            string
     *            
     * @return \AppBundle\Entity\BookingButterfly
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * Get name
     *
     * @return \AppBundle\Entity\BookingButterfly
     */
    public function getName()
    {
        return $this->name;
    }
}
