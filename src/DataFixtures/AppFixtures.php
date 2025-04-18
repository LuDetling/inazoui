<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}
    public function load(ObjectManager $manager): void
    {
        // Create 5 albums
        $albums = [];
        for ($i = 1; $i < 6; $i++) {
            $album = new Album();
            $album->setName("Album " . $i);
            $manager->persist($album);
            $albums[] = $album;
        }

        $users = [];
        // Create 1 admin user
        $admin = new User();
        $admin->setName("ina")
            ->setEmail("ina@zaoui.com")
            ->setPassword($this->hasher->hashPassword($admin, "password"))
            ->setRoles(["ROLE_ADMIN"])
            ->setAdmin(true);
        $manager->persist($admin);


        // Create 50 users
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setName("Invité" . $i)
                ->setEmail("Invite" . $i . "@email.com")
                ->setPassword($this->hasher->hashPassword($user, "password"))
                ->setAdmin(false)
                ->setDescription("Le maître de l''urbanité capturée, explore les méandres des cités avec un regard vif et impétueux, figeant l''énergie des rues dans des instants éblouissants. À travers une technique avant-gardiste, il métamorphose le béton et l''acier en toiles abstraites, révélant l''essence même de l''architecture moderne. Ses clichés transcendent les formes familières pour révéler des perspectives inattendues, offrant une vision nouvelle et captivante du monde urbain.");
            $manager->persist($user);
            $users[] = $user;
        }
        // Create 50 media
        for ($i = 1; $i < 200; $i++) {
            $media = new Media();
            if (!($i % 8)) {
                $media->setAlbum($albums[rand(0, 4)]);
            }
            if ($i < 15) {
                $media->setUser($admin);
            } else {
                $media->setUser($users[array_rand($users)]);
            }
            $media->setPath("/fixtures/" . str_pad($i, 4, '0', STR_PAD_LEFT) . ".jpg");
            $media->setTitle("Image " . $i);
            $manager->persist($media);
        }

        $manager->flush();
    }
}
