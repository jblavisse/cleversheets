<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetId()
    {
        $user = new User();
        $this->assertNull($user->getId());

        // Use reflection to set the private $id property
        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($user, 1);

        $this->assertEquals(1, $user->getId());
    }

    public function testEmail()
    {
        $user = new User();
        $email = 'test@example.com';

        $this->assertSame($user, $user->setEmail($email));
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($email, $user->getUserIdentifier());
    }

    public function testDefaultRoles()
    {
        $user = new User();
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testRoles()
    {
        $user = new User();
        $roles = ['ROLE_ADMIN'];

        $this->assertSame($user, $user->setRoles($roles));

        $expectedRoles = array_unique(array_merge($roles, ['ROLE_USER']));
        $this->assertEquals($expectedRoles, $user->getRoles());
    }

    public function testSetEmptyRoles()
    {
        $user = new User();
        $user->setRoles([]);

        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testPassword()
    {
        $user = new User();
        $password = 'securepassword';

        $this->assertSame($user, $user->setPassword($password));
        $this->assertEquals($password, $user->getPassword());
    }

    public function testEraseCredentials()
    {
        $user = new User();

        // Since eraseCredentials() does nothing in your current implementation,
        // we can test that it doesn't throw any exceptions
        $user->eraseCredentials();

        $this->assertTrue(true);
    }
}
