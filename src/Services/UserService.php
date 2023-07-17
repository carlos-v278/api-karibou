<?php
namespace App\Services;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class  UserService
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function getRandPassword( User $user ):array
    {
        $password = bin2hex(random_bytes(4));
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );
        return [
            'password'=>$password,
            'hashedPassword'=>$hashedPassword

        ];
    }
    public function setPassword( User $user ):array
    {
        $password=$user->getPassword();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );
        return [
            'password'=>$password,
            'hashedPassword'=>$hashedPassword

        ];
    }
}