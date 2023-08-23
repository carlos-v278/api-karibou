<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SyndicateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SyndicateRepository::class)]
#[ApiResource()]
class Syndicate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        'get_building:read'
    ])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'user_syndicate:write',
        'get_building:read'
    ])]
    private ?string $street = null;

    #[Groups([
        'user_syndicate:write',
        'get_building:read'
    ])]
    #[ORM\Column]
    private ?int $streetNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        'user_syndicate:write',
        'get_building:read'
    ])]
    private ?string $siret = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        'user_syndicate:write',
        'get_building:read'
    ])]
    private ?string $siren = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'user_syndicate:write',
        'get_building:read'
    ])]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'user_syndicate:write',
        'get_building:read'
    ])]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'syndicates')]
    #[Groups([
        'get_building:read'
    ])]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'syndicate', targetEntity: Building::class, cascade: ['persist'])]
    #[Groups([
        'user_syndicate:write',
    ])]
    private Collection $buildings;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->buildings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStreetNumber(): ?int
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(int $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): self
    {
        $this->siren = $siren;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addSyndicate($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeSyndicate($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Building>
     */
    public function getBuildings(): Collection
    {
        return $this->buildings;
    }

    public function addBuilding(Building $building): self
    {
        if (!$this->buildings->contains($building)) {
            $this->buildings->add($building);
            $building->setSyndicate($this);
        }

        return $this;
    }

    public function removeBuilding(Building $building): self
    {
        if ($this->buildings->removeElement($building)) {
            // set the owning side to null (unless already changed)
            if ($building->getSyndicate() === $this) {
                $building->setSyndicate(null);
            }
        }

        return $this;
    }
}
