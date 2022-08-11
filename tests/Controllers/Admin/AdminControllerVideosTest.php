<?php

namespace App\Tests\Controllers\Admin;

use App\Entity\Video;
use App\Tests\RoleAdmin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerVideosTest extends WebTestCase
{
  use RoleAdmin;

  public function testDeleteVideo()
  {
    $this->client->request('GET', '/admin/su/delete-video/11/289729765');
    $video = $this->entityManager->getRepository(Video::class)->find(11);
    $this->assertNull($video);
  }
}
