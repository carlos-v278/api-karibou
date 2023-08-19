<?php

namespace App\Subscribers;




use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\User;
use App\Services\UserService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UserRolesSubscriber implements EventSubscriberInterface
{

    public function __construct( private UserService $userService)
    {

    }

    public static function getSubscribedEvents():array
    {
        return [
           KernelEvents::VIEW=>[
               'setUserRoles',
               EventPriorities::PRE_VALIDATE,

           ]
        ];
    }

    public function setUserRoles(ViewEvent $event):void
    {
        $uri= $event->getRequest()->getUri();
        $method=$event->getRequest()->getMethod();
        $user = $event->getControllerResult();
        if(strpos($uri, "/users/syndicate") &&  $method ==='POST' ){
            if($user instanceof  User){
                $user->setRoles([
                    "ROLE_SYNDIC_EDIT",
                    "ROLE_OWNER_CREATE",
                    "ROLE_USER",
                ]);


            }

        }
        if(strpos($uri, "/users/owner") &&  $method ==='POST' ){
            if($user instanceof  User){
                $user->setRoles([
                    "ROLE_OWNER_EDIT",
                    "ROLE_USER",
                    "ROLE_TENANT_CREATE"
                ]);


            }
        }
        if(strpos($uri, "/users/tenant") &&  $method ==='POST' ){
            if($user instanceof  User){
                $user->setRoles([
                    "ROLE_TENANT_EDIT",
                    "ROLE_USER"
                ]);


            }
        }

    }
}