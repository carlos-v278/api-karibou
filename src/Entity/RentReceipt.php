<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\AdvertisementPictureController;
use App\Controller\GenerateRentReceiptController;
use App\Repository\RentReceiptRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[Vich\Uploadable]

#[ApiResource(
    operations: [
        new Post(
            controller: GenerateRentReceiptController::class,
            openapiContext: [
                'summary'=>'Route qui permet d\'ajouter une quittance',
            ],
            denormalizationContext: ['groups'=> ['rent_receipt:write']],
            security: 'is_granted("ROLE_OWNER_EDIT")',
            validationContext: ['groups' => ['Default', 'media_object_create']],
            deserialize: false,
        ),
        new GetCollection(
            normalizationContext: ['groups'=> ['rent_receipt:read']],
            security: 'is_granted("ROLE_USER")',
        ),
    ]
)]
#[ORM\Entity(repositoryClass: RentReceiptRepository::class)]
class RentReceipt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        'rent_receipt:read',
    ])]
    private ?int $id = null;

    #[Groups([
        'rent_receipt:read',
    ])]
    #[ORM\Column(length: 255)]
    private ?string $month = null;

    #[Groups([
        'rent_receipt:write',
    ])]
    #[ORM\ManyToOne(inversedBy: 'rentReceipts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Apartment $apartment = null;


    #[Vich\UploadableField(mapping: "pdf_rent_receipt", fileNameProperty: "file")]
    #[Assert\NotNull(groups: ['media_object_create'])]
    public ?File $urlFile = null;

    #[Groups([
        'rent_receipt:read',
        'rent_receipt:write'
    ])]
    #[ORM\Column(length: 255)]
    private ?string $file = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;

        return $this;
    }



    public function getApartment(): ?Apartment
    {
        return $this->apartment;
    }

    public function setApartment(?Apartment $apartment): self
    {
        $this->apartment = $apartment;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): static
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return  File|null
     */
    public function getUrlFile()
    {
        return $this->urlFile;
    }

    /**
     * @param File|null $urlFile
     * @return  RentReceipt
     */
    public function setUrlFile(?File $file ):RentReceipt
    {
        $this->urlFile = $file;
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


}
