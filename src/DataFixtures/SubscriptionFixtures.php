<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Subscription;
use App\Entity\User;

class SubscriptionFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {

    foreach ($this->getSubscriptionData() as [$userId, $plan, $validTo, $paymentStatus, $freePlanUsed])
    {
      $subscription = new Subscription();
      $subscription->setPlan($plan);
      $subscription->setValidTo($validTo);
      $subscription->setPaymentStatus($paymentStatus);
      $subscription->setFreePlanUsed($freePlanUsed);

      $user = $manager->getRepository(User::class)->find($userId);
      $user->setSubscription($subscription);
      $manager->persist($user);

    }
    $manager->flush();
  }

  private function getSubscriptionData(): array
  {
    return [

      [1, Subscription::getPlanDataNameByIndex(2), (new \DateTimeImmutable())->modify('+100 year'), 'paid',false], // super admin
      [3, Subscription::getPlanDataNameByIndex(0), (new \DateTimeImmutable())->modify('+1 month'), 'paid',true],
      [4, Subscription::getPlanDataNameByIndex(1), (new \DateTimeImmutable())->modify('+1 minute'), 'paid',false]

    ];
  }
}
