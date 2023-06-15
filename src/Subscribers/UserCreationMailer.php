<?php

namespace App\Subscribers;




use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\User;
use App\Services\UserService;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class UserCreationMailer implements EventSubscriberInterface
{

    public function __construct( private UserService $userService, private  MailerInterface $mailer)
    {

    }

    public static function getSubscribedEvents():array
    {
        return [
            KernelEvents::VIEW=>[
                'sendUserCreationMail',
                EventPriorities::POST_VALIDATE,

            ]
        ];
    }

    public function sendUserCreationMail(ViewEvent $event):void
    {
        $uri= $event->getRequest()->getUri();
        $method=$event->getRequest()->getMethod();
        $user = $event->getControllerResult();
        if(strpos($uri, "/users/syndicate") &&  $method ==='POST' ){
            if($user instanceof  User){
                $passwords= $this->userService->getRandPassword($user);
                $user->setPassword($passwords['hashedPassword']);
                $email = (new Email())
                    ->from('karibou.website@outlook.fr')
                    ->to('carlosvieira278@gmail.com')
                    ->subject('creation de compte')
                    ->html("hello world " . $passwords['password']);
                $this->mailer->send($email);
            }

        }
        if(strpos($uri, "/users/") &&  $method ==='PATCH' ){
            if($user instanceof  User){
                $passwords= $this->userService->setPassword($user);
                $user->setPassword($passwords['hashedPassword']);
                $email = (new Email())
                    ->from('karibou.website@outlook.fr')
                    ->to('carlosvieira278@gmail.com')
                    ->subject('creation de compte')
                    ->html("hello world " . $passwords['password']);
                $this->mailer->send($email);
            }
        }
        if(strpos($uri, "/users/tenant") &&  $method ==='POST' ){
            dd('ergr');
        }

    }
}