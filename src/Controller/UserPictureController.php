<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


final class UserPictureController extends AbstractController
{

    public function __invoke(User $user, Request $request)
    {
        $user = $request->attributes->get('data');
        if (!$user instanceof User) {
            throw new \RuntimeException('User entendu');
        }
        $user->setFile($request->files->get('file'));
        $user->setUpdateAt(new \DateTimeImmutable());
        return $user;
    }
}





