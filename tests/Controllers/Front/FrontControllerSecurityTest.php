<?php

namespace App\Tests\Controllers\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerSecurityTest extends WebTestCase
{

  public function testSecureUrls()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/login');
    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', 'Please sign in');
  }

}
