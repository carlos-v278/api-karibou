<?php

namespace App\Controller;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Conversation;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\RentReceipt;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Twig\Environment;
#[AsController]
final class NewUsersConversation extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private IriConverterInterface $iriConverter,
        private ManagerRegistry $doctrine,
        private Security $security,
        private ConversationRepository $conversationRepository,
        private UserRepository $userRepository
    )
    {

    }
    public function __invoke(Request $request): ?Conversation
    {
        $requestData = json_decode($request->getContent(), true);
        $participantIds = $requestData['participants'];

        // Build an array of participant User entities
        $participants = [];
        foreach ($participantIds as $userId) {
            $user = $this->userRepository->find($userId);
            if ($user) {
                $participants[] = $user;
            }
        }

        $currentUser = $this->security->getUser();
        if ($currentUser) {
            $participants[] = $currentUser;
        } else {
            // User is not authenticated
            return null;
        }
        $existingConversation = $this->conversationRepository->findByParticipants($participants);
        if ($existingConversation) {
            return $existingConversation[0];
        }
        // Create a new conversation
        $conversation = new Conversation();

        foreach ($participants as $user) {
            $conversation->addParticipant($user);
        }

        $em = $this->doctrine->getManager();
        $em->persist($conversation);
        $em->flush();

        return $conversation;
    }
}