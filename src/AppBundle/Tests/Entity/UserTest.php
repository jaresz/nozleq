<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\User;

/**
 * Test jednostkowy klasy wszystkich typów użytkowników systemu.
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    const TEST_USER_FIRST_NAME = 'Testowy';
    const TEST_USER_LAST_NAME = 'User';
    
    public function testAdd()
    {
        $testowy = new User();
        $testowy->setFirstName(self::TEST_USER_FIRST_NAME);
        $testowy->setName(self::TEST_USER_LAST_NAME);
        $rp = $testowy->getRapas();
        $this->assertTrue(null != $rp);
        $this->assertTrue('' != $rp);
        $this->assertTrue(strlen($rp) > 5);
        $this->assertEquals(self::TEST_USER_FIRST_NAME, $testowy->getFirstName());
        $this->assertEquals(self::TEST_USER_LAST_NAME, $testowy->getLastName());
        
    }
}
