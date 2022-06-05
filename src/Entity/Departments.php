<?php

namespace App\Entity;

use App\Repository\DepartmentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartmentsRepository::class)]
class Departments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $department_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $department_desc;

    #[ORM\OneToOne(targetEntity: Employee::class, cascade: ['persist', 'remove'])]
    private $manager_id;

    #[ORM\ManyToMany(targetEntity: Tasks::class, mappedBy: 'department_id')]
    private $tasks;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Employee::class)]
    private $employees;


    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->employees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartmentName(): ?string
    {
        return $this->department_name;
    }

    public function setDepartmentName(string $department_name): self
    {
        $this->department_name = $department_name;

        return $this;
    }

    public function getDepartmentDesc(): ?string
    {
        return $this->department_desc;
    }

    public function setDepartmentDesc(string $department_desc): self
    {
        $this->department_desc = $department_desc;

        return $this;
    }

    public function getManagerId(): ?Employee
    {
        return $this->manager_id;
    }

    public function setManagerId(?Employee $manager_id): self
    {
        $this->manager_id = $manager_id;

        return $this;
    }

    /**
     * @return Collection<int, Tasks>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Tasks $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->addDepartmentId($this);
        }

        return $this;
    }

    public function removeTask(Tasks $task): self
    {
        if ($this->tasks->removeElement($task)) {
            $task->removeDepartmentId($this);
        }

        return $this;
    }

    // public function getFullName(): string
    // {
    //     return $this->getEmployeeName() . ' ' . $this->getEmployeeSurname();
    // }

    /**
     * @return Collection<int, Employee>
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
            $employee->setDepartment($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        if ($this->employees->removeElement($employee)) {
            // set the owning side to null (unless already changed)
            if ($employee->getDepartment() === $this) {
                $employee->setDepartment(null);
            }
        }

        return $this;
    }
}
