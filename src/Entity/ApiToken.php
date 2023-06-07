<?php

namespace App\Entity;

use App\Repository\ApiTokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: ApiTokenRepository::class)]
class ApiToken
{
    private const PERSONAL_ACCESS_TOKEN_PREFIX='kcp_';

    public const ROLE_SYNDIC_CREATE = 'ROLE_SYNDIC_CREATE';
    public const ROLE_SYNDIC_EDIT = 'ROLE_SYNDIC_EDIT';
    public const ROLE_OWNER_EDIT = 'ROLE_OWNER_EDIT';
    public const ROLE_OWNER_CREATE = 'ROLE_OWNER_CREATE';
    public const ROLE_TENANT_CREATE = 'ROLE_TENANT_CREATE';
    public const ROLE_TENANT_EDIT = 'ROLE_TENANT_EDIT';
    public const ROLE_USER = 'ROLE_USER';




    public const SCOPES = [
        self::ROLE_TENANT_EDIT,
        self::ROLE_TENANT_CREATE,
        self::ROLE_SYNDIC_CREATE,
        self::ROLE_SYNDIC_EDIT,
        self::ROLE_OWNER_CREATE,
        self::ROLE_OWNER_EDIT,
        self::ROLE_USER,

    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'apiTokens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ownedBy = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expiresAt = null;

    #[ORM\Column(length: 68)]
    private ?string $token;

    #[ORM\Column]
    private array $scopes = [];

    public function __construct(string $tokenType = self:: PERSONAL_ACCESS_TOKEN_PREFIX)
    {
        $this->token = $tokenType.bin2hex(random_bytes(32));
        $this->expiresAt = new \DateTimeImmutable() ;
        $newDate = $this->expiresAt->add(new \DateInterval('PT1H'));
        dump($newDate);
        $this->expiresAt = $newDate;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwnedBy(): ?User
    {
        return $this->ownedBy;
    }

    public function setOwnedBy(?User $ownedBy): self
    {
        $this->ownedBy = $ownedBy;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeImmutable $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getScopes(): array
    {
        return $this->scopes;
    }

    public function setScopes(array $scopes): self
    {
        $this->scopes = $scopes;

        return $this;
    }
    public function isValid():bool
    {
        return $this->expiresAt === null || $this->expiresAt > new \DateTimeImmutable();
    }


}
