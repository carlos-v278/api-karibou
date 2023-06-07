<?php

namespace App\Controller;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login',methods: ['POST'])]
    public function login(
        Request $request,
        IriConverterInterface $iriConverter,
        #[CurrentUser] User $user = null,
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $doctrine
    ): Response
    {
        $em = $doctrine->getManager();
        if (!$user) {
            return $this->json([
                'error' => 'Invalid login request: check that the Content-Type header is "application/json".',
            ], 401);
        }
        //decode the password and check it's right
        $decoded = json_decode($request->getContent());
        $password = $decoded->password;
        $passwordIsValid = $passwordHasher->isPasswordValid(
            $user,
            $password,
        );
        //if the password it's good set and send the token
        if($passwordIsValid){
            $token = $user->getValidTokenStrings();
            //check if a valid token exist
            if(empty($user->getValidTokenStrings())){
                $apiToken = new ApiToken();
                $apiToken->setOwnedBy($user);
                $token = [$apiToken->getToken()];
                $apiToken->setScopes([
                    $apiToken::ROLE_TENANT,
                ]);
                $em->persist($apiToken);
                $em->flush();

            }
            return $this->json([
                'success' => 'Connexion Success',
                'token'=>$token,
            ], 200);
        } else{
            return $this->json([
                'error' => 'Wrong user or password',
            ], 401);
        }

        return $this->json([
            'Location' => $iriConverter->getIriFromResource($user),
        ], 401);
    }

    #[Route('/logout', name: 'app_logout',methods: ['POST'])]
    public function logout(): void
    {
        throw new \Exception('This should never be reached');
    }
    #[Route('/registration', name: 'app_registration',methods: ['POST'])]
    public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());

        $email = $decoded->email;
        $plaintextPassword = $decoded->password;
        $userName=$decoded->username;
        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword,
        );
        $user->setPassword($hashedPassword);
        $user->setEmail($email);
        $user->setUsername($userName);
        $em->persist($user);
        $em->flush();

        return $this->json(['message' => 'Registered successfully!']);
    }

}
