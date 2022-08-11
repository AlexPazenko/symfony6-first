<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Subscription;
use App\Controller\Traits\SaveSubscription;


class SecurityController extends AbstractController
{
  use SaveSubscription;

  private $doctrine;

  public function __construct(ManagerRegistry $doctrine) {
    $this->doctrine = $doctrine;
  }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $helper)
    {
        return $this->render('front/login.html.twig', [
            'error' => $helper->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/register/{plan}", name="register", defaults={"plan": null})
     */
    public function register(UserPasswordHasherInterface $passwordHasher, Request $request, SessionInterface $session, $plan, ManagerRegistry $doctrine)
    {
        if( $request->isMethod('GET')  )
        {
          $session->set('planName',$plan);
          $session->set('planPrice', Subscription::getPlanDataPriceByName($plan));
        }
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $doctrine->getManager();
    
            $user->setName($request->request->all()['user']['name']);
            $user->setLastName($request->request->all()['user']['last_name']);
            $user->setEmail($request->request->all()['user']['email']);
            $password = $passwordHasher->hashPassword($user, $request->request->all()['user']['password']['first']);
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

          $date = new \DateTimeImmutable();
          $date->modify('+1 month');
          $subscription = new Subscription();
          $subscription->setValidTo($date);
          $subscription->setPlan($session->get('planName'));
          if($plan == Subscription::getPlanDataNameByIndex(0)) // free plan
          {
            $subscription->setFreePlanUsed(true);
            $subscription->setPaymentStatus('paid');
          }
          else
          {
            $subscription->setFreePlanUsed(false);
          }
          $user->setSubscription($subscription);

          $entityManager->persist($user);
          $entityManager->flush();

          $this->loginUserAutomatically($user, $password);

          return $this->redirectToRoute('admin_main_page');
        }

      if($this->isGranted('IS_AUTHENTICATED_REMEMBERED') && $plan == Subscription::getPlanDataNameByIndex(0)) // free plan
      {
        $this->saveSubscription($plan, $this->getUser(), $this->doctrine);

        return $this->redirectToRoute('admin_main_page');

      }
      elseif($this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
      {
        return $this->redirectToRoute('payment');
      }

      return $this->render('front/register.html.twig',['form'=>$form->createView()]);
    }
   

    private function loginUserAutomatically($user, $password)
    {
        $token = new UsernamePasswordToken(
            $user,
            'main', // security.yaml
            $user->getRoles()
        );
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main',serialize($token));
    }

    protected function get(string $id)
    {
      return $this->container->get($id);
    }
}
