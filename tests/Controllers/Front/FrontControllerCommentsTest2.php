<?php

namespace App\Tests\Controllers\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerCommentsTest2 extends WebTestCase
{

    public function testNotLoggedInUser()
    {
        $client = static::createClient();
        $client->followRedirects();
        
        $crawler = $client->request('GET', '/video-details/16');

        $form = $crawler->selectButton('Add')->form([
            'comment' => 'Test comment'
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('h1','Please sign in');

    }

}

