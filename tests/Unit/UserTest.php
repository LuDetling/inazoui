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

        $this->assertContains('ROLE_USER', $user->getRoles());

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

        $user->isAdmin(false);
        $this->assertFalse($user->isAdmin());

    }
}
