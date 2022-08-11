<?php

namespace App\Listeners;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Mailer;
use Twig\Extra\Inky\InkyExtension;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Security\Core\Security;
use App\Entity\Video;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Symfony\Bridge\Twig\Mime\BodyRenderer;


class NewVideoListener
{
  /**
   * @var Security
   */
  private $security;
  public function __construct(Security $security)
  {
    $this->security = $security;
  }

  public function postPersist(LifecycleEventArgs $args)
  {

    $entity = $args->getObject();

    // only act on some "Product" entity
    if (!$entity instanceof Video) {
      return;
    }


    $entityManager = $args->getObjectManager();
    // ... do something with the Product

    /*$user = $this->security->getUser();*/

    $entityManager = $args->getObjectManager();
    $users = $entityManager->getRepository(User::class)->findAll();


    foreach($users as $user)
    {
      $message = (new Email())
        ->from('send@example.com')
        ->to($user->getEmail())
        ->subject('Time for Symfony Mailer!')
        ->html('<p>See Twig integration for better HTML integration!</p>');

      /*$dsn = 'smtp://mailhog:1025';*/
      $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
      $mailer = new Mailer($transport);
      $mailer->send($message);
    }

  }
}
