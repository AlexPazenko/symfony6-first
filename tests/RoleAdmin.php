<?php
namespace App\Tests;

use App\Repository\UserRepository;

trait RoleAdmin {
  public function setUp(): void
  {

    /*self::bootKernel();
    // returns the real and unchanged service container
    $container = self::$kernel->getContainer();
    // gets the special container that allows fetching private services
    $container = self::$container;
    $cache = self::$container->get('App\Utils\Interfaces\CacheInterface');
    $this->cache = $cache->cache;
    $this->cache->clear();*/


    $this->client = static::createClient();
    $userRepository = static::getContainer()->get(UserRepository::class);
    $testUser = $userRepository->findOneByEmail('jw@symf4.loc');
    $this->client->loginUser($testUser);
    $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
  }

  /*public function tearDown()
  {
    parent::tearDown();
    $this->cache->clear();
    // $this->entityManager->rollback();
    $this->entityManager->close();
    $this->entityManager = null; // avoid memory leaks
  }*/
}