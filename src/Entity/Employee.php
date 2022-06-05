<?php

namespace App\Entity;

use App\Entity\EmployeeCategory;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;
use App\Repository\EmployeeRepository;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ORM\Table(name: "employees")]
#[Broadcast]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $employee_code;

    #[ORM\Column(type: 'string', length: 50)]
    private $employee_name;

    #[ORM\Column(type: 'string', length: 50)]
    private $employee_surname;

    #[ORM\Column(type: 'string', length: 50)]
    private $employee_sexe;

    #[ORM\Column(type: 'date')]
    private $employee_dob;

    #[ORM\Column(type: 'string', length: 100)]
    private $employee_email;

    #[ORM\Column(type: 'string', length: 50)]
    private $employee_photo;

    #[ORM\Column(type: 'integer')]
    private $employee_phone;

    #[ORM\Column(type: 'date')]
    private $hire_date;

    #[ORM\Column(type: 'string', length: 100)]
    private $employee_adress;

    #[ORM\Column(type: 'string', length: 50)]
    private $employee_status;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'employees')]
    private $role;


    #[ORM\ManyToOne(targetEntity: Departments::class, inversedBy: 'employees')]
    private $department;

    #[ORM\ManyToOne(targetEntity: EmployeeCategory::class, inversedBy: 'employees')]
    private $kpa;


    public function __construct()
    {
        $this->role = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployeeName(): ?string
    {
        return $this->employee_name;
    }

    public function setEmployeeName(string $employee_name): self
    {
        $this->employee_name = $employee_name;

        return $this;
    }

    public function getEmployeeSurname(): ?string
    {
        return $this->employee_surname;
    }

    public function setEmployeeSurname(string $employee_surname): self
    {
        $this->employee_surname = $employee_surname;

        return $this;
    }

    public function getEmployeeSexe(): ?string
    {
        return $this->employee_sexe;
    }

    public function setEmployeeSexe(string $employee_sexe): self
    {
        $this->employee_sexe = $employee_sexe;

        return $this;
    }

    public function getEmployeeDob(): ?\DateTimeInterface
    {
        return $this->employee_dob;
    }

    public function setEmployeeDob(\DateTimeInterface $employee_dob): self
    {
        $this->employee_dob = $employee_dob;

        return $this;
    }

    public function getEmployeeEmail(): ?string
    {
        return $this->employee_email;
    }

    public function setEmployeeEmail(string $employee_email): self
    {
        $this->employee_email = $employee_email;

        return $this;
    }

    public function getEmployeePhoto(): ?string
    {
        return $this->employee_photo;
    }

    public function setEmployeePhoto(string $employee_photo): self
    {
        $this->employee_photo = $employee_photo;

        return $this;
    }

    public function getEmployeePhone(): ?int
    {
        return $this->employee_phone;
    }

    public function setEmployeePhone(int $employee_phone): self
    {
        $this->employee_phone = $employee_phone;

        return $this;
    }

    public function getHireDate(): ?\DateTimeInterface
    {
        return $this->hire_date;
    }

    public function setHireDate(\DateTimeInterface $hire_date): self
    {
        $this->hire_date = $hire_date;

        return $this;
    }

    public function getEmployeeAdress(): ?string
    {
        return $this->employee_adress;
    }

    public function setEmployeeAdress(string $employee_adress): self
    {
        $this->employee_adress = $employee_adress;

        return $this;
    }

    public function getEmployeeStatus(): ?string
    {
        return $this->employee_status;
    }

    public function setEmployeeStatus(string $employee_status): self
    {
        $this->employee_status = $employee_status;

        return $this;
    }

    public function getEmployeeCode(): ?string
    {
        return $this->employee_code;
    }

    public function setEmployeeCode(string $employee_code): self
    {
        $this->employee_code = $employee_code;

        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function addRole(Role $role): self
    {
        if (!$this->role->contains($role)) {
            $this->role[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        $this->role->removeElement($role);

        return $this;
    }

    public function getFullName(): string
    {
        return $this->getEmployeeName() . ' ' . $this->getEmployeeSurname();
    }


    public function getDepartment(): ?Departments
    {
        return $this->department;
    }

    public function setDepartment(?Departments $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getKpa(): ?EmployeeCategory
    {
        return $this->kpa;
    }

    public function setKpa(?EmployeeCategory $kpa): self
    {
        $this->kpa = $kpa;

        return $this;
    }
}
