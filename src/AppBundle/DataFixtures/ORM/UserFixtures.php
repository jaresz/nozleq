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

        $usrFx=[];
        $unr=0;
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
       
        $unr++;
        $usrFx[$unr] = new User();
        $uname = "recepcjonista";
        $usrFx[$unr]->setUsername($uname);
        $usrFx[$unr]->setFirstName("Sobiesław");
        $usrFx[$unr]->setName("Radziwiłowicz");
        $usrFx[$unr]->setEmail("recepcjonista@listy.internetowe.pl");
        $usrFx[$unr]->setEnabled(true);
        $usrFx[$unr]->setPlainPassword("reseg923ibd454253!");
        $manager->persist($usrFx[$unr]);
        $usrFx[$unr]->addRole('ROLE_CLERK');
         
        $this->addReference('User:'.$uname, $usrFx[$unr]);
        $unr++;
        $usrFx[$unr] = new User();
        $uname = "stefan";
        $usrFx[$unr]->setUsername($uname);
        $usrFx[$unr]->setFirstName("Stefan");
        $usrFx[$unr]->setName("Korbiszczykowicz");
        $usrFx[$unr]->setEmail("korba@listy.internetowe.pl");
        $usrFx[$unr]->setEnabled(true);
        $usrFx[$unr]->setPlainPassword("2raz2dwa3trzy!");
        $manager->persist($usrFx[$unr]);
        $usrFx[$unr]->addRole('ROLE_USER');
         
        $this->addReference('User:'.$uname, $usrFx[$unr]);
        
        $manager->flush();
       
        
    }

    public function getOrder()
    {
        return 20;
    }
}
