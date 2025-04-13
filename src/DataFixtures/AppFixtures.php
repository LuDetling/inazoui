<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setName("ina")
        ->setEmail("ina@zaoui.com")
        ->setPassword($this->hasher->hashPassword($admin, "password"))
        ->setRoles(["ROLE_ADMIN"])
        ->setAdmin(true);
        $manager->persist($admin);

        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setName("Invité" . $i)
                ->setEmail("Invité" . $i . "@email.com")
                ->setPassword($this->hasher->hashPassword($user, "password"))
                ->setAdmin(false)
                ->setDescription("Le maître de l''urbanité capturée, explore les méandres des cités avec un regard vif et impétueux, figeant l''énergie des rues dans des instants éblouissants. À travers une technique avant-gardiste, il métamorphose le béton et l''acier en toiles abstraites, révélant l''essence même de l''architecture moderne. Ses clichés transcendent les formes familières pour révéler des perspectives inattendues, offrant une vision nouvelle et captivante du monde urbain.");

            $manager->persist($user);
        }
        
        $manager->flush();
    }
}
