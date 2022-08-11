<?php

namespace App\Tests\Controllers\Admin;

use App\Entity\Category;
use App\Tests\RoleAdmin;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;

class AdminControllerCategoriesTest extends WebTestCase
{
  use RoleAdmin;

  /*public function setUp(): void
  {
    $this->client = static::createClient();
    $userRepository = static::getContainer()->get(UserRepository::class);
    $testUser = $userRepository->findOneByEmail('jw@symf4.loc');
    $this->client->loginUser($testUser);
    $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
  }*/

  public function testTextOnPage()
  {
    $crawler = $this->client->request('GET', '/admin/su/categories');
    $this->assertSame('Categories list', $crawler->filter('h2')->text());
  }

  public function testNumberOfItems()
  {
    $crawler = $this->client->request('GET', '/admin/su/categories');
    $this->assertCount(1, $crawler->filter('h1'));
    $this->assertSame('Dashboard', $crawler->filter('h1')->text());
    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', 'Dashboard');
  }

  public function testNewCategory()
  {
    $crawler = $this->client->request('GET', '/admin/su/categories');
    $form = $crawler->selectButton('Add')->form([
      'category[parent]' => 0,
      'category[name]' => 'Other electronics',
    ]);
    $this->client->submit($form);

    $crawler = $this->client->request('GET', '/admin/su/categories');
    $this->assertSelectorTextContains('h2', 'Categories list');
    $category = $this->entityManager->getRepository(Category::class)->findOneBy(['name'=>'Other electronics']);
    $this->assertNotNull($category);
    $this->assertSame('Other electronics', $category->getName());
  }

    public function testEditCategory()
    {
      $crawler = $this->client->request('GET', '/admin/su/edit-category/1');
      $this->assertSame('Editing category', $crawler->filter('h2')->text());
      $form = $crawler->selectButton('Save')->form([
        'category[name]' => 'Electronics 2',
        'category[parent]' => "2",
      ]);
      $this->client->submit($form);
      $category = $this->entityManager->getRepository(Category::class)->findOneBy(['name'=>'Electronics 2']);
      $category = $this->entityManager->getRepository(Category::class)->find(1);
      $this->assertSame('Electronics 2', $category->getName());
    }

    public function testDeleteCategory()
    {
      $crawler = $this->client->request('GET', '/admin/su/delete-category/20');
      $category = $this->entityManager->getRepository(Category::class)->find(20);
      $this->assertNull($category);
    }
}

