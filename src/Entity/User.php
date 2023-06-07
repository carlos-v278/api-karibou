<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Contstrains as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: "users/syndicate",
            security: 'is_granted("ROLE_SYNDIC_CREATE")',
            name: 'new_user_syndicate',

        ),
        new Put(
            uriTemplate: "users/syndicate",
            security: 'is_granted("ROLE_SYNDIC_EDIT")',
            name: 'edit_user_syndicate',
        ),
        new Post(
            uriTemplate: "users/owner",
            security: 'is_granted("ROLE_OWNER_CREATE")',
            name: 'new_user_owner',
        ),
        new Put(
            uriTemplate: "users/owner",
            security: 'is_granted("ROLE_OWNER_EDIT")',
            name: 'edit_user_owner',
        ),
        new Post(
            uriTemplate: "users/tenant",
            security: 'is_granted("ROLE_TENANT_CREATE")',
            name: 'new_user_tenant',
        ),
        new Put(
            uriTemplate: "users/tenant",
            security: 'is_granted("ROLE_TENANT_EDIT")',
            name: 'edit_user_tenant',
        ),
        new GetCollection(
            uriTemplate: "users/{building-id}",
            security: 'is_granted("ROLE_USER")',
        )
    ]
)]
#[UniqueEntity(fields: ['email'],message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['username'],message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    #[Assert\NotBlank]
    private ?string $username = null;


    #[ORM\OneToMany(mappedBy: 'ownedBy', targetEntity: ApiToken::class)]
    private Collection $apiTokens;

    private ?array $accesTokenScopes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;
    public function __construct()
    {
        $this->apiTokens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        if(null === $this->accesTokenScopes){
            $roles = $this->roles;
            $roles[] = 'ROLE_FULL_USER';
        } else{
            $roles = $this->accesTokenScopes;
        }

        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }




    /**
     * @return Collection<int, ApiToken>
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens->add($apiToken);
            $apiToken->setOwnedBy($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->removeElement($apiToken)) {
            // set the owning side to null (unless already changed)
            if ($apiToken->getOwnedBy() === $this) {
                $apiToken->setOwnedBy(null);
            }
        }

        return $this;
    }
    public function getValidTokenStrings():array
    {
        return $this->getApiTokens()
            ->filter(fn (ApiToken $token) =>$token->isValid())
            ->map(fn (ApiToken $token) =>$token->getToken())
            ->toArray();


    }
    public function markAsTokenAuthenticated(array $scopes):void
    {
        $this->accesTokenScopes = $scopes;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }
}