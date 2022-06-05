<?php

namespace App\Entity;

use App\Repository\MailsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MailsRepository::class)]
class Mails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $send_from;

    #[ORM\Column(type: 'datetime')]
    private $send_time;

    #[ORM\Column(type: 'string', length: 100)]
    private $send_to;

    #[ORM\Column(type: 'boolean')]
    private $is_read;

    #[ORM\Column(type: 'string', length: 50)]
    private $placeholder;

    #[ORM\Column(type: 'string', length: 100)]
    private $mail_object;

    #[ORM\OneToMany(mappedBy: 'mail_id', targetEntity: Messages::class)]
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSendFrom(): ?string
    {
        return $this->send_from;
    }

    public function setSendFrom(string $send_from): self
    {
        $this->send_from = $send_from;

        return $this;
    }

    public function getMailObject(): ?string
    {
        return $this->mail_object;
    }
    public function getIsRead(): ?bool
    {
        return $this->is_read;
    }

    public function setIsRead(bool $is_read): self
    {
        $this->is_read = $is_read;

        return $this;
    }
    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function setPlaceholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function setMailObject(string $mail_object): self
    {
        $this->mail_object = $mail_object;

        return $this;
    }
    public function getSendTime(): ?\DateTimeInterface
    {
        return $this->send_time;
    }

    public function setSendTime(\DateTimeInterface $send_time): self
    {
        $this->send_time = $send_time;

        return $this;
    }
    public function getSendTo(): ?string
    {
        return $this->send_to;
    }

    public function setSendTo(string $send_to): self
    {
        $this->send_to = $send_to;

        return $this;
    }

    /**
     * @return Collection<int, Messages>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Messages $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setMailId($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getMailId() === $this) {
                $message->setMailId(null);
            }
        }

        return $this;
    }
}
