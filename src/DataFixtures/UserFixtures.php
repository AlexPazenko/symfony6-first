<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
  public function __construct(UserPasswordHasherInterface $passwordHasher)
  {
    $this->password_hasher = $passwordHasher;
  }
  public function load(ObjectManager $manager): void
  {
    foreach ($this->getUserNewData() as [$name, $last_name, $email, $password, $api_key, $roles])
    {
      $userNew = new User();
      $userNew->setName($name);
      $userNew->setLastName($last_name);
      $userNew->setEmail($email);
      $userNew->setPassword($this->password_hasher->hashPassword($userNew, $password));
      $userNew->setVimeoApiKey($api_key);
      $userNew->setRoles($roles);
      $manager->persist($userNew);
    }

    $manager->flush();
  }

  private function getUserNewData(): array
  {
    return [
      ['John', 'Wayne', 'jw@symf4.loc', 'passw', 'hjd8dehdh', ['ROLE_ADMIN']],
      ['John', 'Wayne2', 'jw2@symf4.loc', 'passw', null, ['ROLE_ADMIN']],
      ['John', 'Doe', 'jd@symf4.loc', 'passw', null, ['ROLE_USER']],
      ['Ted', 'Bundy', 'tb@symf4.loc', 'passw', null, ['ROLE_USER']]
    ];
  }
}
