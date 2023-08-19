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
    private const FROM_EMAIL = 'karibou.website@outlook.fr';
    private const EMAIL_SUBJECT = 'creation de compte';
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
        if(
            strpos($uri, "/users/owner") ||
            strpos($uri, "/users/syndicate") ||
            strpos($uri, "/users/tenant")
            &&  $method ==='POST' ){
            if($user instanceof  User){
                $passwords = $this->userService->getRandPassword($user);
                $user->setPassword($passwords['hashedPassword']);
                $this->sendEmail($user,$passwords['password']);
            }

        }
        if(strpos($uri, "/users/") &&  $method ==='PATCH' ){
            if($user instanceof  User){
                $passwords= $this->userService->setPassword($user);
                $user->setPassword($passwords['hashedPassword']);
                $this->sendEmail($user,$passwords['password']);
            }
        }


    }
    private function sendEmail(User $user, $password): void
    {
        $emailContent = "hello world " . $password ;

        $email = (new Email())
            ->from(self::FROM_EMAIL)
            ->to('carlosvieira278@gmail.com')
            ->subject(self::EMAIL_SUBJECT)
            ->html($emailContent);

        $this->mailer->send($email);
    }
}