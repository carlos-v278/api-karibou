<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ApartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ApartmentRepository::class)]
#[ApiResource]
class Apartment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['user_syndicate:read', 'user_syndicate:write'])]
    private ?int $number = null;

    #[ORM\Column]
    #[Groups(['user_syndicate:read', 'user_syndicate:write'])]
    private ?int $floor = null;

    #[ORM\Column(nullable: true)]
    private ?int $rent = null;

    #[ORM\Column(nullable: true)]
    private ?int $extra_charge = null;

    #[ORM\ManyToOne(inversedBy: 'apartments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Building $building = null;

    #[ORM\OneToMany(mappedBy: 'apartment', targetEntity: RentReceipt::class)]
    private Collection $rentReceipts;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(['user_syndicate:read', 'user_syndicate:write'])]
    private ?User $owner = null;

    #[ORM\OneToMany(mappedBy: 'apartment', targetEntity: User::class)]
    private Collection $tenants;

    public function __construct()
    {
        $this->rentReceipts = new ArrayCollection();
        $this->tenants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getRent(): ?int
    {
        return $this->rent;
    }

    public function setRent(?int $rent): self
    {
        $this->rent = $rent;

        return $this;
    }

    public function getExtraCharge(): ?int
    {
        return $this->extra_charge;
    }

    public function setExtraCharge(?int $extra_charge): self
    {
        $this->extra_charge = $extra_charge;

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
     * @return Collection<int, RentReceipt>
     */
    public function getRentReceipts(): Collection
    {
        return $this->rentReceipts;
    }

    public function addRentReceipt(RentReceipt $rentReceipt): self
    {
        if (!$this->rentReceipts->contains($rentReceipt)) {
            $this->rentReceipts->add($rentReceipt);
            $rentReceipt->setApartment($this);
        }

        return $this;
    }

    public function removeRentReceipt(RentReceipt $rentReceipt): self
    {
        if ($this->rentReceipts->removeElement($rentReceipt)) {
            // set the owning side to null (unless already changed)
            if ($rentReceipt->getApartment() === $this) {
                $rentReceipt->setApartment(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getTenants(): Collection
    {
        return $this->tenants;
    }

    public function addTenant(User $tenant): self
    {
        if (!$this->tenants->contains($tenant)) {
            $this->tenants->add($tenant);
            $tenant->setApartment($this);
        }

        return $this;
    }

    public function removeTenant(User $tenant): self
    {
        if ($this->tenants->removeElement($tenant)) {
            // set the owning side to null (unless already changed)
            if ($tenant->getApartment() === $this) {
                $tenant->setApartment(null);
            }
        }

        return $this;
    }
}
