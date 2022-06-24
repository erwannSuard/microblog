<?php

namespace App\Entity;

use App\Repository\PrivateMessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrivateMessageRepository::class)]
#[ORM\HasLifecycleCallbacks]
class PrivateMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messagesSent')]
    #[ORM\JoinColumn(nullable: false)]
    private $author;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messagesReceived')]
    #[ORM\JoinColumn(nullable: false)]
    private $receiver;

    #[ORM\Column(type: 'boolean')]
    private $isRead;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\OneToMany(mappedBy: 'originalMessage', targetEntity: MessageResponse::class, orphanRemoval: true)]
    private $messageResponses;

    public function __construct()
    {
        $this->messageResponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function isIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * @return Collection<int, MessageResponse>
     */
    public function getMessageResponses(): Collection
    {
        return $this->messageResponses;
    }

    public function addMessageResponse(MessageResponse $messageResponse): self
    {
        if (!$this->messageResponses->contains($messageResponse)) {
            $this->messageResponses[] = $messageResponse;
            $messageResponse->setOriginalMessage($this);
        }

        return $this;
    }

    public function removeMessageResponse(MessageResponse $messageResponse): self
    {
        if ($this->messageResponses->removeElement($messageResponse)) {
            // set the owning side to null (unless already changed)
            if ($messageResponse->getOriginalMessage() === $this) {
                $messageResponse->setOriginalMessage(null);
            }
        }

        return $this;
    }
}
