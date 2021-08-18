<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\URLRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=URLRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Url
{
    public function __construct() {
        $this->setCreateDate(new \DateTime());
        $this->setHits(0);
    }
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateAccess() {
        $this->setLastAccessed(new \DateTime());
        $hits = $this->getHits();
        $this->setHits($hits++);
        return $this;
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(message = "The url '{{ value }}' is not a valid url",)
     */
    private $longUrl;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $shortUrl;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastAccessed;

    /**
     * @ORM\Column(type="integer")
     */
    private $hits;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLongUrl(): ?string
    {
        return $this->longUrl;
    }

    public function setLongUrl(string $longUrl): self
    {
        $this->longUrl = $longUrl;

        return $this;
    }

    public function getShortUrl(): ?string
    {
        return $this->shortUrl;
    }

    public function setShortUrl(string $shortUrl): self
    {
        $this->shortUrl = $shortUrl;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getLastAccessed(): ?\DateTimeInterface
    {
        return $this->lastAccessed;
    }

    public function setLastAccessed(\DateTimeInterface $lastAccessed): self
    {
        $this->lastAccessed = $lastAccessed;

        return $this;
    }

    public function getHits(): ?int
    {
        return $this->hits;
    }

    public function setHits(int $hits): self
    {
        $this->hits = $hits;

        return $this;
    }

    public function __toString() : string
    {
        return $this->getId() . $this->getLongUrl() . $this->getShortUrl();
    }
}
