<?php

namespace App\Tests\Controllers\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\RoleUser;

class AdminControllerSubscriptionTest extends WebTestCase
{
  use RoleUser;

  public function testDeleteSubscription()
  {

    $crawler = $this->client->request('GET', '/admin/');

    $link = $crawler
      ->filter('a:contains("cancel plan")')
      ->link();

    $this->client->click($link);

    $this->client->request('GET', '/video-list/category/toys,2');

    $this->assertSelectorTextContains('p.card-text','Video for MEMBERS only.');

  }

}
