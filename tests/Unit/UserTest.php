<?php

namespace App\Tests\Unit;

use App\Entity\Media;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserProperties(): void
    {
        $user = new User();

        $user->setRoles(['ROLE_USER_TEST']);
        $this->assertContains('ROLE_USER_TEST', $user->getRoles());

        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getEmail());

        $user->setPassword('securepassword');
        $this->assertEquals('securepassword', $user->getPassword());

        $user->setName('John Doe');
        $this->assertEquals('John Doe', $user->getName());

        $user->setDescription('This is a test user.');
        $this->assertEquals('This is a test user.', $user->getDescription());

        $user->setMedias(new ArrayCollection([new Media()]));
        $this->assertInstanceOf(ArrayCollection::class, $user->getMedias());

        $user->setIsActive(true);
        $this->assertTrue($user->isActive());

        $user->setAdmin(false);
        $this->assertFalse($user->isAdmin());

    }
}
