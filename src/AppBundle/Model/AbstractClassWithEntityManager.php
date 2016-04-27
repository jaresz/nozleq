<?php
namespace AppBundle\Model;
/*
 * Abstrakcyjna klasa z wstrzyknięciem zależności do managera encji
 * Odpowiednia np. do tworzenia usług
 */
abstract class AbstractClassWithEntityManager
{
    
    protected $entityManager;
    
    public function setEntityManager($pem)
    {
        $this->entityManager = $pem;
    }
    
    public function  getEntityManager()
    {
        return $this->entityManager;
    }
    
    public function __construct($em) {
        $this->setEntityManager( $em );
    }
    
}
