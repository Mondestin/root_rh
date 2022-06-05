<?php

namespace App\Entity;

use App\Entity\Mails;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MessagesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: MessagesRepository::class)]
class Messages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $mail_body;

    #[ORM\OneToMany(mappedBy: 'message_id', targetEntity: Files::class)]
    private $files;

    #[ORM\ManyToOne(targetEntity: Mails::class, inversedBy: 'messages')]
    private $mail_id;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getMailBody(): ?string
    {
        return $this->mail_body;
    }

    public function setMailBody(string $mail_body): self
    {
        $this->mail_body = $mail_body;

        return $this;
    }


    public function getMailId(): ?Mails
    {
        return $this->mail_id;
    }

    public function setMailId(?Mails $mail_id): self
    {
        $this->mail_id = $mail_id;

        return $this;
    }
}
