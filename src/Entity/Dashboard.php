<?php

namespace App\Entity;

use App\Repository\DashboardRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DashboardRepository::class, readOnly: true)]
#[ORM\Table(name: "v_dashboard")]
class Dashboard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $nb_employee;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $nb_department;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $nb_customer;

    #[ORM\Column(type: 'decimal', precision: 6, scale: 2, nullable: true)]
    private $salary_avg;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbEmployee(): ?int
    {
        return $this->nb_employee;
    }

    public function setNbEmployee(?int $nb_employee): self
    {
        $this->nb_employee = $nb_employee;

        return $this;
    }

    public function getNbDepartment(): ?int
    {
        return $this->nb_department;
    }

    public function setNbDepartment(?int $nb_department): self
    {
        $this->nb_department = $nb_department;

        return $this;
    }

    public function getNbCustomer(): ?int
    {
        return $this->nb_customer;
    }

    public function setNbCustomer(?int $nb_customer): self
    {
        $this->nb_customer = $nb_customer;

        return $this;
    }

    public function getSalaryAvg(): ?string
    {
        return $this->salary_avg;
    }

    public function setSalaryAvg(?string $salary_avg): self
    {
        $this->salary_avg = $salary_avg;

        return $this;
    }
}
