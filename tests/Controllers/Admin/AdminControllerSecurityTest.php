<?php

namespace App\Tests\Controllers\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminControllerSecurityTest extends WebTestCase
{
  /**
   * @dataProvider getUrlsForRegularUsers
   */
  public function testAccessDeniedForRegularUsers(string $httpMethod, string $url)
  {
    $this->client = static::createClient();
    $userRepository = static::getContainer()->get(UserRepository::class);
    $testUser = $userRepository->findOneByEmail('jd@symf4.loc');
    $this->client->loginUser($testUser);

    $this->client->request($httpMethod, $url);
    $this->assertSame(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
  }

  public function getUrlsForRegularUsers()
  {
    yield ['GET', '/admin/su/categories'];
    yield ['GET', '/admin/su/edit-category/1'];
    yield ['GET', '/admin/su/delete-category/1'];
    yield ['GET', '/admin/su/users'];
    yield ['GET', '/admin/su/upload-video-locally'];
  }

  public function testAdminSu()
  {
    $this->client = static::createClient();
    $userRepository = static::getContainer()->get(UserRepository::class);
    $testUser = $userRepository->findOneByEmail('jw@symf4.loc');
    $this->client->loginUser($testUser);

    $crawler = $this->client->request('GET', '/admin/su/categories');

    $this->assertSame('Categories list', $crawler->filter('h2')->text());
  }
}
