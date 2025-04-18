<?php

namespace App\Tests\Unit;

use App\Entity\Album;
use PHPUnit\Framework\TestCase;

class AlbumTest extends TestCase
{

    public function testAlbum(): void
    {
        $album = new Album();

        $album->setName('Album 1');
        $this->assertEquals('Album 1', $album->getName());
    }
}
