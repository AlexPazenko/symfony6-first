<?php
namespace App\Controller\Traits;

use App\Entity\Subscription;


trait SaveSubscription {


    private function saveSubscription($plan, $user, $doctrine)
    {

      $date = new \DateTimeImmutable();
      $date->modify('+1 month');
      $subscription = $user->getSubscription();

      if(null === $subscription)
      {
        $subscription = new Subscription;
      }

      if($subscription->getFreePlanUsed() && $plan == Subscription::getPlanDataNameByIndex(0)) // free plan
      {
        return;
      }

      $subscription->setValidTo($date);
      $subscription->setPlan($plan);


      if($plan == Subscription::getPlanDataNameByIndex(0)) // free plan
      {
        $subscription->setFreePlanUsed(true);
        $subscription->setPaymentStatus('paid');
      }

      $subscription->setPaymentStatus('paid'); // tmp

      $user->setSubscription($subscription);

      $entityManager = $doctrine->getManager();
      $entityManager->persist($user);
      $entityManager->flush();

    }

}