<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Room;

class RoomFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $test_ob = [
            'Alizarynowy',
            'Amarantowy',
            'Biskupi',
            'Bordowy, Bordo',
            'Buraczkowy',
            'Burgund',
            'Ceglasty',
            'Cynobrowy',
            'Czerwony',
            'Eozyna',
            'Fuksjowy',
            'Kardynalski',
            'Karmazynowy',
            'Karminowy',
            'Łososiowy',
            'Magentowy',
            'Makowy',
            'Pąsowy',
            'Poziomkowy',
            'Rdzawy',
            'Pompejański',
            'Różowy',
            'Rubinowy',
            'Rudy',
            'Szkarłatny',
            'Tango',
            'Truskawkowy',
            'Wiśniowy',
        ];
        
        $te_objs=[];
        $nr=0;
        foreach ($test_ob as $obi=>$onename)
        {
            $nr++;
            $te_objs[$obi] = new Room();            
            $te_objs[$obi]->setName($onename.' Pokój');
            $te_objs[$obi]->setRoomNumber($nr);
            $te_objs[$obi]->setAirConditioned(true);
            $te_objs[$obi]->setCapacity(2);
            $te_objs[$obi]->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit.\n Praesent id eros volutpat, ultrices velit non, efficitur ante.\n Praesent ornare lorem auctor tortor efficitur, in lobortis est porttitor. Nam consequat nulla at ligula lacinia congue. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Integer vulputate rhoncus ultricies.");
            $te_objs[$obi]->setCreatedByUser( $manager->merge($this->getReference('User:First')) );
            $manager->persist($te_objs[$obi]);
            
        }

        $manager->flush();
        
       
        
    }

    public function getOrder()
    {
        return 30;
    }
}
            
        
