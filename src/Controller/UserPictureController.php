<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class UserPictureController extends AbstractController
{


    public function __invoke(User $user, Request $request, EntityManagerInterface $entityManager)
    {
        $user = $request->attributes->get('data');
        if(!$user instanceof  User){
            throw new \RuntimeException('User entendu');
        }
        $user->setFile($request->files->get('file'));
        $entityManager->persist();
        $entityManager->flush();
        return $user;
    }
}





