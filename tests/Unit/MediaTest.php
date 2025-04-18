<?php

namespace App\Tests\Unit;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{

    public function getMedia(): Media
    {
        $media = new Media();
        $media->setPath('/fixtures/0001.jpg');
        $media->setTitle('Image 1');
        $media->setUser(new User());
        $media->setAlbum(new Album());
        return $media;
    }

    public function testMediaIsValid(): void
    {
        $media = $this->getMedia();
        self::assertEquals('/fixtures/0001.jpg', $media->getPath());
        self::assertEquals('Image 1', $media->getTitle());
        self::assertInstanceOf(User::class, $media->getUser());
        self::assertInstanceOf(Media::class, $media);
        self::assertInstanceOf(Album::class, $media->getAlbum());
    }
}
