<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\MeController;
use App\Controller\MyProfileController;
use App\Controller\UserPictureController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[Vich\Uploadable]

#[ORM\Table(name: '`user`')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: "users/syndicate",
            openapiContext: [
                'summary'=>'Route qui permet de créer un utilisateur et un syndicat',
            ],
            normalizationContext: ['groups'=> ['user_syndicate:write' ]],
            denormalizationContext: ['groups'=> ['user_syndicate:write']],
            security: 'is_granted("ROLE_USER")',
            name: 'new_user_syndicate',

        ),
        new Patch(
            uriTemplate: "users/{id}",
            requirements: ['id' => '\d+'],
            openapiContext: [
                'summary'=>'Route qui permet de modifier les informations du User',
            ],
            normalizationContext: ['groups'=> ['user:edit']],
            denormalizationContext: ['groups'=> ['user:edit']],
            security: 'is_granted("ROLE_USER") and object == user',
            name: 'edit_user',
        ),
        new Post(
            uriTemplate: "users/owner",
            openapiContext: [
                'summary'=>'Route qui permet de creer un propriétaire',
            ],
            normalizationContext: ['groups'=> ['user_owner:write']],
            denormalizationContext: ['groups'=> ['user_owner:write']],
            security: 'is_granted("ROLE_OWNER_CREATE")',
            name: 'new_user_owner',
        ),
        new Post(
            uriTemplate: "users/tenant",
            openapiContext: [
                'summary'=>'Route qui permet de creer un locataire',
            ],
            normalizationContext: ['groups'=> ['user_tenant:write']],
            denormalizationContext: ['groups'=> ['user_tenant:write']],
            security: 'is_granted("ROLE_TENANT_CREATE")',
            name: 'new_user_tenant',
        ),

        new Post(
            uriTemplate: "users/{id}/picture",
            controller: UserPictureController::class,
            normalizationContext: ['groups'=> ['user_picture:read']],
            denormalizationContext: ['groups'=> ['user_picture:write']],
            validationContext: ['groups' => ['Default', 'media_object_create']],
            deserialize: false
        ),
        new GetCollection(
            uriTemplate: "users/",
            normalizationContext: ['groups'=> ['user:read']],
            denormalizationContext: ['groups'=> ['user:read']],
            security: 'is_granted("ROLE_TENANT_EDIT")',
        ),
        new GetCollection(
            uriTemplate: "me/",
            controller: MeController::class,
            paginationEnabled: false,
            normalizationContext: ['groups'=> ['user_me:read']],
            security: 'is_granted("ROLE_USER")',
        )
    ]
)]
/* Supprimer  la get All users  car get par imeuble*/
#[UniqueEntity(fields: ['email'],message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['username'],message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['apartment:read',
        'user_picture:read',
        'user_picture:write',
        'user_me:read'
    ])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups([ 'user_syndicate:write',
        'user:read',
        'user_owner:write',
        'user_tenant:write',
        'apartment:read',
        'user_me:read'
    ])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user_me:read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: true)]
    #[Groups([
        'user_syndicate:write',
        'user:read',
        'user:edit'
    ])]
    private ?string $password = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    #[Groups(['user:edit',
        'user_syndicate:read',
        'user_syndicate:write',
        'user_owner:write',
        'user:read',
        'user_tenant:write',
        'apartment:read',
        'user_me:read'
    ])]
    private ?string $username = null;


    #[ORM\OneToMany(mappedBy: 'ownedBy', targetEntity: ApiToken::class, cascade: ['persist'])]
    private Collection $apiTokens;

    private ?array $accesTokenScopes = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        'user_syndicate:read',
        'user_syndicate:write',
        'user_owner:write',
        'user:read',
        'user:edit',
        'user_tenant:write',
        'apartment:read',
        'user_me:read'
        ])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        'user_syndicate:read',
        'user_syndicate:write',
        'user_owner:write',
        'user:read',
        'user:edit',
        'user_tenant:write',
        'apartment:read',
        'user_me:read'
    ])]
    private ?string $lastname = null;

    #[ORM\ManyToMany(targetEntity: Syndicate::class, inversedBy: 'users',cascade: ['persist'])]
    #[Groups([
        'user_syndicate:read',
        'user_syndicate:write',
        'user:read'
    ])]
    private Collection $syndicates;

    #[Vich\UploadableField(mapping: "user_pic", fileNameProperty: "picture")]
    #[Assert\NotNull(groups: ['media_object_create'])]
    public ?File $file = null;

    #[ORM\Column(length: 255, nullable: true,)]
    #[Groups([
        'user:edit',
        'apartment:read',
        'user_picture:read',
        'user_picture:write',
        'user_me:read'
    ])]
    private ?string $picture = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'tenants')]
    private ?Apartment $location = null;

    #[Groups([
        'user:edit',
        'user_owner:write',
        'user:read'
    ])]
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Apartment::class, cascade: ['persist'])]
    private Collection $properties;

    #[ORM\Column(nullable: false)]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column]
    private ?\DateTimeImmutable $updateAt;


    public function __construct()
    {
        $this->createdAt= new \DateTimeImmutable();
        $this->updateAt= new \DateTimeImmutable();
        $this->apiTokens = new ArrayCollection();
        $this->syndicates = new ArrayCollection();
        $this->properties = new ArrayCollection();
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
    public function getOldTokenRoles():array
    {
        return $this->getApiTokens()
            ->filter(fn (ApiToken $token) => !empty($token->getScopes()))
            ->map(fn (ApiToken $token) =>$token->getScopes())
            ->toArray();


    }
    public function invalidateAllTokens(EntityManagerInterface $entityManager):void
    {
        $tokens = $this->getApiTokens();
        foreach ($tokens as $token) {
            if ($token->isValid()) {
                $token->setExpiresAt(new \DateTimeImmutable());
            }
        }

        $entityManager->flush();

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

    /**
     * @return Collection<int, Syndicate>
     */
    public function getSyndicates(): Collection
    {
        return $this->syndicates;
    }

    public function addSyndicate(Syndicate $syndicate): self
    {
        if (!$this->syndicates->contains($syndicate)) {
            $this->syndicates->add($syndicate);
        }

        return $this;
    }

    public function removeSyndicate(Syndicate $syndicate): self
    {
        $this->syndicates->removeElement($syndicate);

        return $this;
    }



    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getLocation(): ?Apartment
    {
        return $this->location;
    }

    public function setLocation(?Apartment $location): static
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection<int, Apartment>
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Apartment $property): static
    {
        if (!$this->properties->contains($property)) {
            $this->properties->add($property);
            $property->setOwner($this);
        }

        return $this;
    }

    public function removeProperty(Apartment $property): static
    {
        if ($this->properties->removeElement($property)) {
            // set the owning side to null (unless already changed)
            if ($property->getOwner() === $this) {
                $property->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return  File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     * @return  User
     */
    public function setFile(?File $file ):User
    {
        $this->file = $file;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt)
    {
        $this->updateAt = $updateAt;
        return $this;
    }




}
