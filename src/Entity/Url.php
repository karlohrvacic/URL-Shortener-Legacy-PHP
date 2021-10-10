<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
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
 *              "method"= "GET",
 *              "controller"= UrlGetAction::class,
 *              },
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
        $this->setLastAccessed(new DateTime());
        $this->setVisits(0);
    }

    public function updateAccess(): static
    {
        $this->lastAccessed = new DateTime('now');
        $this->visits++;
        return $this;
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ApiProperty(identifier=false)
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(message = "The url '{{ value }}' is not a valid url",)
     */
    private ?string $longUrl = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @ApiProperty(identifier=true)
     * @Assert\Regex(
     *     pattern="/^[A-Za-z0-9-]+$/"
     * )
     */
    private ?string $shortUrl = null;

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
    private ?int $visits;

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

    public function getVisits(): ?int
    {
        return $this->visits;
    }

    public function setVisits(int $visits): self
    {
        $this->visits = $visits;
        return $this;
    }

    public function __toString() : string
    {
        return $this->getId() . $this->getLongUrl() . $this->getShortUrl();
    }

}
