<?php
namespace AppBundle\Entity\Interfaces;


interface BookableResourceInterface
{
   /**
    * @return integer  resource unique number
    */
    public function getId();
    public function getName();
    
    //public function isReservedOn();
    
}

