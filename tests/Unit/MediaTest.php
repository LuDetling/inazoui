<?php

namespace App\Tests\Unit;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaTest extends TestCase
{

    public function testMedia(): void
    {
        $media = new Media();

        $uploadedFile = new UploadedFile(__DIR__.'/../fixtures/0001.jpg', '0001.jpg');
        $media->setFile($uploadedFile);
        $this->assertInstanceOf(UploadedFile::class, $media->getFile());

        $media->setPath('/fixtures/0001.jpg');
        $this->assertEquals('/fixtures/0001.jpg', $media->getPath());

        $media->setTitle('Image 1');
        $this->assertEquals('Image 1', $media->getTitle());

        $media->setUser(new User());
        $this->assertInstanceOf(User::class, $media->getUser());

        $media->setAlbum(new Album());
        $this->assertInstanceOf(Album::class, $media->getAlbum());

    }

}
