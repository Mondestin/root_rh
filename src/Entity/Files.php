<?php

namespace App\Entity;

use App\Repository\FilesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilesRepository::class)]
class Files
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $file_name;

    #[ORM\ManyToOne(targetEntity: Messages::class, inversedBy: 'files')]
    private $message_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): self
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getMessageId(): ?Messages
    {
        return $this->message_id;
    }

    public function setMessageId(?Messages $message_id): self
    {
        $this->message_id = $message_id;

        return $this;
    }
}
