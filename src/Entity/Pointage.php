<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PointageRepository;

//Mondestin
#[ORM\Entity(repositoryClass: PointageRepository::class)]
#[ORM\Table(name: "pointages")]
#[ORM\HasLifecycleCallbacks]
class Pointage
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $employee_id;

    #[ORM\Column(type: 'date')]
    private $pointing_date;

    #[ORM\Column(type: 'time')]
    private $start_time;

    #[ORM\Column(type: 'time')]
    private $end_time;

    #[ORM\Column(type: 'string', length: 30)]
    private $status;

    #[ORM\Column(type: 'text')]
    private $justify;

    #[ORM\Column(type: 'text')]
    private $comments;

    #[ORM\Column(type: 'string', length: 30)]
    private $validate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployeeId(): ?string
    {
        return $this->employee_id;
    }

    public function setEmployeeId(string $employee_id): self
    {
        $this->employee_id = $employee_id;

        return $this;
    }

    public function getPointingDate(): ?\DateTimeInterface
    {
        return $this->pointing_date;
    }

    public function setPointingDate(\DateTimeInterface $pointing_date): self
    {

        $this->pointing_date = $pointing_date;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->end_time;
    }

    public function setEndTime(\DateTimeInterface $end_time = null): self
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getJustify(): ?string
    {
        return $this->justify;
    }

    public function setJustify(string $justify): self
    {
        $this->justify = $justify;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getValidate(): ?string
    {
        return $this->validate;
    }

    public function setValidate(string $validate): self
    {
        $this->validate = $validate;

        return $this;
    }
}
