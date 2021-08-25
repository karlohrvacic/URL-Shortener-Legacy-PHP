<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UrlRepository;
use App\Controller\UrlPostAction;
use App\Controller\UrlGetAction;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     itemOperations={
 *      "get"={
 *              "method"="GET",
 *              "controller"=UrlGetAction::class
 *              },
 *          "delete"
 *     },
 *     collectionOperations={
 *          "post"={
 *              "method"="POST",
 *              "controller"=UrlPostAction::class,
 *              "deserialize"=false
 *              }
 *     }
 *
 *   )
 * @ORM\Entity(repositoryClass=UrlRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Url
{
    public function __construct() {
        $this->setCreateDate(new DateTime());
        $this->setHits(0);
    }
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateAccess(): static
    {
        $this->setLastAccessed(new DateTime());
        $hits = $this->getHits();
        $this->setHits($hits++);
        return $this;
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(message = "The url '{{ value }}' is not a valid url",)
     */
    private ?string $longUrl;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private ?string $shortUrl;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $createDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $lastAccessed;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $hits;

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

    public function getCreateDate(): ?DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getLastAccessed(): ?DateTimeInterface
    {
        return $this->lastAccessed;
    }

    public function setLastAccessed(DateTimeInterface $lastAccessed): self
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

    public function addHit(): self
    {
        $this->hits++;
        return $this;
    }

    public function lastAccessedNow() : self
    {
        $this->lastAccessed = new DateTime('now');
        return $this;
    }
}
