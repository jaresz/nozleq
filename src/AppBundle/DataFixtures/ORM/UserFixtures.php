<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $unr=0;
        $usrFx[$unr] 
        = new User();
        $usrFx[$unr]->setUsername("admin");
        $usrFx[$unr]->setFirstName("Administrator");
        $usrFx[$unr]->setLastName("Główny");
        $usrFx[$unr]->setEmail("admin@listy.internetowe.pl");
        $usrFx[$unr]->setEnabled(true);
        $usrFx[$unr]->setAdmin(true); 
        $usrFx[$unr]->setPlainPassword("RazZimelen(ioWy)45!");        
        $manager->persist($usrFx[$unr]);
        $usrFx[$unr]->addRole('ROLE_ADMIN');
       

        $manager->flush();
       
        
    }

    public function getOrder()
    {
        return 20;
    }
}
