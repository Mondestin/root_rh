<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomersRepository;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CustomersRepository::class)]
class Customers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $company_name;

    #[ORM\Column(type: 'string', length: 50)]
    private $customer_name;

    #[ORM\Column(type: 'string', length: 50)]
    private $customer_surname;

    #[ORM\Column(type: 'string', length: 100)]
    private $customer_email;

    #[ORM\Column(type: 'integer')]
    private $customer_phone_number;

    #[ORM\Column(type: 'string', length: 255)]
    private $customer_adress;

    #[ORM\Column(type: 'string', length: 50)]
    private $customer_code;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Tasks::class)]
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(string $company_name): self
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customer_name;
    }

    public function setCustomerName(string $customer_name): self
    {
        $this->customer_name = $customer_name;

        return $this;
    }

    public function getCustomerSurname(): ?string
    {
        return $this->customer_surname;
    }

    public function setCustomerSurname(string $customer_surname): self
    {
        $this->customer_surname = $customer_surname;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customer_email;
    }

    public function setCustomerEmail(string $customer_email): self
    {
        $this->customer_email = $customer_email;

        return $this;
    }

    public function getCustomerPhoneNumber(): ?int
    {
        return $this->customer_phone_number;
    }

    public function setCustomerPhoneNumber(int $customer_phone_number): self
    {
        $this->customer_phone_number = $customer_phone_number;

        return $this;
    }

    public function getCustomerAdress(): ?string
    {
        return $this->customer_adress;
    }

    public function setCustomerAdress(string $customer_adress): self
    {
        $this->customer_adress = $customer_adress;

        return $this;
    }

    public function getCustomerCode(): ?string
    {
        return $this->customer_code;
    }

    public function setCustomerCode(string $customer_code): self
    {
        $this->customer_code = $customer_code;

        return $this;
    }


    public function getFullName(): string
    {
        return $this->getCustomerName() . ' ' . $this->getCustomerSurname();
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
            $task->setCustomer($this);
        }

        return $this;
    }

    public function removeTask(Tasks $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getCustomer() === $this) {
                $task->setCustomer(null);
            }
        }

        return $this;
    }
}
