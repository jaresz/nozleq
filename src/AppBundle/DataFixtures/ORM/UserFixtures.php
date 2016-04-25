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
        $usrFx=[];
        $usrFx[$unr] = new User();
        $uname = "admin";
        $usrFx[$unr]->setUsername($uname);
        $usrFx[$unr]->setFirstName("Administrator");
        $usrFx[$unr]->setName("Główny");
        $usrFx[$unr]->setEmail("admin@listy.internetowe.pl");
        $usrFx[$unr]->setEnabled(true);
        $usrFx[$unr]->setPlainPassword("RazZimelen(ioWy)45!");        
        $manager->persist($usrFx[$unr]);
        $usrFx[$unr]->addRole('ROLE_ADMIN');
       
        $this->addReference('User:First', $usrFx[$unr]);
        $this->addReference('User:'.$uname, $usrFx[$unr]);

        $manager->flush();
       
        
    }

    public function getOrder()
    {
        return 20;
    }
}
