<?php

namespace App\Tests\Controllers\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\RoleUser;

class AdminControllerTranslationTest extends WebTestCase {

  use RoleUser;

  public function testTranslations()
  {
    $crawler = $this->client->request('GET', '/pl/admin/');
    $this->assertSame('Mój profil delete account', $crawler->filter('h2')->text());
  }
}
