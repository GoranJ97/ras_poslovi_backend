<?php

namespace App\Entity;

use App\Repository\UserImagesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=UserImagesRepository::class)
 * @Vich\Uploadable()
 */
class UserImages extends BaseEntity
{

    /**
     * @Vich\UploadableField(mapping="user_images", fileNameProperty="url")
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $url;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="image")
     */
    private $user;

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
