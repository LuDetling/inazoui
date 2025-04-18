<?php

namespace App\Tests\Unit;

use App\Entity\Album;
use PHPUnit\Framework\TestCase;

class AlbumTest extends TestCase
{

    public function getAlbum(): Album
    {
        $album = new Album();
        $album->setName('Album 1');
        return $album;
    }
    public function testCreateAlbum(): void
    {
        $album = $this->getAlbum();
        self::assertInstanceOf(Album::class, $album);
        self::assertEquals("Album 1", $album->getName());
    }
}
