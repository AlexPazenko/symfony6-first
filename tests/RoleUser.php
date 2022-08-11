<?php
namespace App\Tests;

use App\Repository\UserRepository;

trait RoleUser {
  public function setUp(): void
  {
    $this->client = static::createClient();
    $userRepository = static::getContainer()->get(UserRepository::class);
    $testUser = $userRepository->findOneByEmail('jd@symf4.loc');
    $this->client->loginUser($testUser);
    $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
  }

  /*  public function tearDown(): void
    {
      parent::tearDown();
      $this->entityManager->close();
      $this->entityManager = null; // avoid memory leaks
    }*/
}