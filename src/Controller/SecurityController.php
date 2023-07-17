<?php

namespace App\Controller;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\ApiToken;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

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
            $token = array_values($user->getValidTokenStrings());
            $token = (count($token) >0 ? $token[0] : $token) ;
            //check if a valid token exist
            if(empty($user->getValidTokenStrings())){
                $apiToken = new ApiToken();
                $apiToken->setOwnedBy($user);
                $token = $apiToken->getToken();
                $apiToken->setScopes( $user->getRoles());
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

    #[Route('/logout', name: 'app_logout')]
    public function logout(Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        if ($user instanceof User) {
            $user->invalidateAllTokens($entityManager);
            $entityManager->flush();
        }

        return $this->json([
            'logout' => 'success',
        ], 200);
    }

    #[Route('/users/profile', name: 'app_my_profile', methods: ['GET'])]
    public function profile(Security $security): Response
    {
        // controller can be blank: it will never be called!
        // logout the user in on the current firewall
        $user = $security->getUser();

        // you can also disable the csrf logout

        return $this->json([
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'firstName'=> $user->getFirstName(),
            'lastName'=> $user->getLastName(),
            'roles'=> $user->getRoles(),
            'id'=> $user->getId(),
            'picture'=> $user->getPicture(),



        ], 200);
    }





}
