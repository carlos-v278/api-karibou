<?php

namespace App\Entity;

use App\Repository\AdvertisementPictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: AdvertisementPictureRepository::class)]
class AdvertisementPicture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        'get_advertisement:read',
        'get_building:read'
    ])]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: "advertisement_pic", fileNameProperty: "file")]
    #[Assert\NotNull(groups: ['media_object_create'])]
    public ?File $urlFile = null;
    #[ORM\Column(length: 255)]
    #[Groups([
        'get_advertisement:read',
        'get_building:read'
    ])]
    private ?string $file = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Advertisement $advertisement = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getAdvertisement(): ?Advertisement
    {
        return $this->advertisement;
    }

    public function setAdvertisement(?Advertisement $advertisement): static
    {
        $this->advertisement = $advertisement;

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
     * @return  AdvertisementPicture
     */
    public function setUrlFile(?File $file ):AdvertisementPicture
    {
        $this->urlFile = $file;
        return $this;
    }
}
