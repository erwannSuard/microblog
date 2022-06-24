<?php

namespace App\Entity;

use App\Repository\MessageResponseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageResponseRepository::class)]
#[ORM\HasLifecycleCallbacks]
class MessageResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'datetime_immutable')]
    private $responseCreatedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messageResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private $author;

    #[ORM\ManyToOne(targetEntity: PrivateMessage::class, inversedBy: 'messageResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private $originalMessage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getResponseCreatedAt(): ?\DateTimeImmutable
    {
        return $this->responseCreatedAt;
    }

    #[ORM\PrePersist]
    public function setResponseCreatedAt(): self
    {
        $this->responseCreatedAt = new \DateTimeImmutable;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getOriginalMessage(): ?PrivateMessage
    {
        return $this->originalMessage;
    }

    public function setOriginalMessage(?PrivateMessage $originalMessage): self
    {
        $this->originalMessage = $originalMessage;

        return $this;
    }
}
