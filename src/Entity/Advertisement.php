<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\AdvertisementPictureController;
use App\Controller\UserPictureController;
use App\Repository\AdvertisementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AdvertisementRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            openapiContext: [
                'summary'=>'Route qui permet de créer une annonce',
            ],
            denormalizationContext: ['groups'=> ['advertisement:write']],
            security: 'is_granted("ROLE_USER")',
            name: 'new_advertisement',
        ),
        new Post(
            uriTemplate: "advertisements/{id}/picture",
            controller: AdvertisementPictureController::class,
            openapiContext: [
                'summary'=>'Route qui permet d\'ajouter une photo à une annonce',
            ],
            normalizationContext: ['groups'=> ['advertisement_picture:read']],
            denormalizationContext: ['groups'=> ['advertisement_picture:write']],
            validationContext: ['groups' => ['Default', 'media_object_create']],
            deserialize: false
        ),
        new Get(
            openapiContext: [
                'summary'=>'Route qui permet récuperer une annonce en fonction de l\'id',
            ],
            normalizationContext: ['groups'=> ['get_advertisement:read']],
            security: 'is_granted("ROLE_USER")',
            name: 'get_advertisement',

        ),

    ]
)]
class Advertisement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    #[Groups([
        'get_advertisement:read',
        'get_building:read'
    ])]
    private ?int $id = null;

    #[Groups([
        'get_building:read',
        'get_advertisement:read',
        'advertisement:write'
    ])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;


    #[ORM\Column]
    #[Groups([
        'get_advertisement:read',
        'get_building:read'
    ])]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updateAt = null;

    #[Groups([
        'advertisement:write',
        'get_advertisement:read',
        'get_building:read'
    ])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Groups([
        'advertisement:write',
        'get_advertisement:read',
        'get_building:read',
    ])]
    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    #[Groups([
        'advertisement:write',
        'get_advertisement:read',
        'get_building:read',
    ])]
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[Groups([
        'advertisement:write',
        'get_advertisement:read'
    ])]
    #[ORM\ManyToOne(inversedBy: 'advertisements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Building $building = null;

    #[Groups([
        'get_advertisement:read',
        'get_building:read',
    ])]
    #[ORM\OneToMany(mappedBy: 'advertisement', targetEntity: AdvertisementPicture::class, orphanRemoval: true)]
    private Collection $pictures;

    #[Groups([
        'advertisement:write',
        'get_advertisement:read',
        'advertisement:read',
        'get_building:read'
    ])]
    #[ORM\ManyToOne(inversedBy: 'advertisements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->publishedAt= new \DateTimeImmutable();
        $this->updateAt= new \DateTimeImmutable();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    public function setBuilding(?Building $building): self
    {
        $this->building = $building;

        return $this;
    }

    /**
     * @return Collection<int, AdvertisementPicture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(AdvertisementPicture $picture): static
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setAdvertisement($this);
        }

        return $this;
    }

    public function removePicture(AdvertisementPicture $picture): static
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getAdvertisement() === $this) {
                $picture->setAdvertisement(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
