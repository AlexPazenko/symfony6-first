<?php

namespace App\Tests\Controllers\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Subscription;
use App\Tests\RoleUser;

class FrontControllerSubscriptionTest extends WebTestCase
{

  use RoleUser;

  /**
   * @dataProvider urlsWithVideo
   */
  public function testLoggedInUserDoesNotSeeTextForNoMembers($url)
  {
    $this->client->request('GET', $url);
    $this->assertSelectorNotExists( 'p.card-text' );
  }

  public function urlsWithVideo()
  {
    yield ['/video-list/category/movies,4'];
    yield ['/search-results?query=movies'];
  }


  public function testExpiredSubscription()
  {
    $subscription = $this->entityManager
      ->getRepository(Subscription::class)
      ->find(2);

    $invalid_date = new \DateTimeImmutable();
    $invalid_date->modify('-1 day');
    $subscription->setValidTo($invalid_date);

    $this->entityManager->persist($subscription);
    $this->entityManager->flush();

    $this->client->request('GET', '/video-list/category/movies,4');

    $this->assertSelectorTextContains('p.card-text','Video for MEMBERS only.');

  }


  public function urlsWithVideo2()
  {
    yield ['/video-list/category/toys,2/2'];
    yield ['/search-results?query=Movies+3'];
    yield ['/video-details/2#video_comments'];
  }

}
