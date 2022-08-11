<?php

namespace App\Tests\Controllers\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Subscription;

class FrontControllerSubscriptionTest2 extends WebTestCase
{

  /**
   * @dataProvider urlsWithVideo
   */
  public function testNotLoggedInUserSeesTextForNoMembers($url)
  {
    $client = static::createClient();
    $client->request('GET', $url);
    $this->assertSelectorTextContains('p.card-text','Video for MEMBERS only.');

  }

  public function urlsWithVideo()
  {
    yield ['/video-list/category/movies,4'];
    yield ['/search-results?query=movies'];
  }


  /**
   * @dataProvider urlsWithVideo2
   */
  public function testNotLoggedInUserSeesVideosForNoMembers($url)
  {
    $client = static::createClient();
    $client->request('GET', $url);
    /*$this->assertContains( 'https://player.vimeo.com/video/113716040', $client->getResponse()->getContent() );*/
    $this->assertSelectorTextContains('p.card-text','Video for MEMBERS only.');

  }

  public function urlsWithVideo2()
  {
    yield ['/video-list/category/toys,2/2'];
    yield ['/search-results?query=Movies+3'];
    yield ['/video-details/2#video_comments'];
  }

}
