<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\BuildingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BuildingRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups'=> ['building:read']],

        )
    ]
)]
class Building
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['apartment:read','building:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user_syndicate:read', 'user_syndicate:write', 'building:read','apartment:read'])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user_syndicate:read', 'user_syndicate:write','apartment:read','building:read'])]
    private ?string $country = null;

    #[ORM\Column]
    #[Groups(['user_syndicate:read', 'user_syndicate:write','apartment:read','building:read'])]
    private ?int $zipcode = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user_syndicate:read', 'user_syndicate:write','apartment:read','building:read'])]
    private ?string $street = null;

    #[ORM\Column]
    #[Groups(['user_syndicate:read', 'user_syndicate:write','apartment:read','building:read'])]
    private ?int $number = null;

    #[ORM\ManyToOne(inversedBy: 'buildings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Syndicate $syndicate = null;

    #[ORM\OneToMany(mappedBy: 'building', targetEntity: Advertisement::class, orphanRemoval: true)]
    private Collection $advertisements;

    #[ORM\OneToMany(mappedBy: 'building', targetEntity: Apartment::class, orphanRemoval: true)]
    private Collection $apartments;

    public function __construct()
    {
        $this->advertisements = new ArrayCollection();
        $this->apartments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getSyndicate(): ?Syndicate
    {
        return $this->syndicate;
    }

    public function setSyndicate(?Syndicate $syndicate): self
    {
        $this->syndicate = $syndicate;

        return $this;
    }

    /**
     * @return Collection<int, Advertisement>
     */
    public function getAdvertisements(): Collection
    {
        return $this->advertisements;
    }

    public function addAdvertisement(Advertisement $advertisement): self
    {
        if (!$this->advertisements->contains($advertisement)) {
            $this->advertisements->add($advertisement);
            $advertisement->setBuilding($this);
        }

        return $this;
    }

    public function removeAdvertisement(Advertisement $advertisement): self
    {
        if ($this->advertisements->removeElement($advertisement)) {
            // set the owning side to null (unless already changed)
            if ($advertisement->getBuilding() === $this) {
                $advertisement->setBuilding(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Apartment>
     */
    public function getApartments(): Collection
    {
        return $this->apartments;
    }

    public function addApartment(Apartment $apartment): self
    {
        if (!$this->apartments->contains($apartment)) {
            $this->apartments->add($apartment);
            $apartment->setBuilding($this);
        }

        return $this;
    }

    public function removeApartment(Apartment $apartment): self
    {
        if ($this->apartments->removeElement($apartment)) {
            // set the owning side to null (unless already changed)
            if ($apartment->getBuilding() === $this) {
                $apartment->setBuilding(null);
            }
        }

        return $this;
    }
}
