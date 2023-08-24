<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\AdvertisementPictureController;
use App\Controller\NewUsersConversation;
use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            controller: NewUsersConversation::class,
            openapiContext: [
                'summary'=>'Route qui permet créer une conversation entre deux utilisateurs',
            ],
            normalizationContext: ['groups'=> ['conversation_post:read']],
            denormalizationContext: ['groups'=> ['conversation_post:write']],
            deserialize: false,
        ),
        new GetCollection(
            openapiContext: [
                'summary'=>'Route qui permet de récupérer toutes les conversations personnelles',
            ],
            normalizationContext: ['groups'=> ['all_conversation:read']],
            security: 'is_granted("ROLE_USER")',
            name: 'get_all_conversation',
        ),

    ]
)]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        'conversation_post:read',
        'all_conversation:read'
    ])]
    private ?int $id = null;

    #[Groups([
        'all_conversation:read'
    ])]
    #[ORM\Column(nullable: true)]
    private ?int $lastMessageId = null;

    #[Groups([
        'conversation_post:write',
        'all_conversation:read'
    ])]
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'conversations')]
    private Collection $participants;

    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastMessageId(): ?int
    {
        return $this->lastMessageId;
    }

    public function setLastMessageId(?int $lastMessageId): static
    {
        $this->lastMessageId = $lastMessageId;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }
}
