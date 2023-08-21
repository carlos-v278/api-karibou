<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\GenerateRentReceiptController;
use App\Repository\RentReceiptRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Post(
            controller: GenerateRentReceiptController::class,
            openapiContext: [
                'summary'=>'Route qui permet de creer une quittance',
            ],
            denormalizationContext: ['groups'=> ['rent_receipt:write']],
            security: 'is_granted("ROLE_OWNER_EDIT")',
            name: 'new_user_tenant',
        ),
    ]
)]
#[ORM\Entity(repositoryClass: RentReceiptRepository::class)]
class RentReceipt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups([
        'rent_receipt:write',
    ])]
    #[ORM\Column(length: 255)]
    private ?string $month = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;
    #[Groups([
        'rent_receipt:write',
    ])]
    #[ORM\ManyToOne(inversedBy: 'rentReceipts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Apartment $apartment = null;

    #[Groups([
        'rent_receipt:write',
    ])]
    #[ORM\Column(length: 255)]
    private ?string $file = null;

    public function __construct()
    {
        $this->date= new \DateTimeImmutable();
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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

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
}
