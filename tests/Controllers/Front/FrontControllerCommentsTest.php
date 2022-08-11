<?php

namespace App\Tests\Controllers\Front;

use App\Tests\RoleUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Rollback;

class FrontControllerCommentsTest extends WebTestCase
{
    use RoleUser;

    public function testNewCommentAndNumberOfComments()
    {
 
        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/video-details/16');

        $form = $crawler->selectButton('Add')->form([
            'comment' => 'Test comment',
        ]);
        $this->client->submit($form);

        $this->assertSelectorTextContains('div.media-body','Test comment');

        $crawler = $this->client->request('GET', '/video-list/category/toys,2');
        $this->assertSame('Comments (1)', $crawler->filter('a.ml-1')->text());
        
    }
}

