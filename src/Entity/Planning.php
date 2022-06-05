<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use DateInterval;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

use function PHPUnit\Framework\isNull;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('calendar')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    #[Groups('calendar')]
    private $start;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups('calendar')]
    private $end;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups('calendar')]
    private $title;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups('calendar')]
    private $color;

    #[ORM\Column(type: 'boolean')]
    #[Groups('calendar')]
    private $allDay;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups('calendar')]
    private $textColor;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'plannings')]
    #[Groups('calendar')]
    private $attendees;

    public function __construct()
    {
        $this->attendees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    // This will set the date start property by a date in string format
    public function setStartByDateString(string $start): self
    {
        $start = explode(" ", $start, 2)[0];
        $this->start = new \DateTime($start);
        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end = null): self
    {
        if (isNull($end))
            $this->setEndDefault($end);
        else
            $this->end = $end;

        return $this;
    }

    // This will set the date end property by a date in string format
    public function setEndByDateString(string $end): self
    {
        if (strcmp("undefined", $end) == 0 || strcmp("null", $end) == 0)
            $this->setEndDefault($end);
        else {
            $end = explode(" ", $end, 2)[0];
            $this->end = new \DateTime($end);
        }
        return $this;
    }

    private function setEndDefault($end)
    {
        $end = \DateTime::createFromInterface($this->start);
        $end->add(new DateInterval('PT1H'));
        $this->end = $end;
    }

    public function gettitle(): ?string
    {
        return $this->title;
    }

    public function settitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getAllDay(): ?bool
    {
        return $this->allDay;
    }

    public function setAllDay(string $allDay): self
    {
        if (strcmp($allDay, "true") == 0) {
            $this->allDay = true;
            $this->end = null;
        } else
            $this->allDay = false;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(?string $textColor): self
    {
        $this->textColor = $textColor;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAttendees(): Collection
    {
        return $this->attendees;
    }

    public function addAttendee(User $attendee): self
    {
        if (!$this->attendees->contains($attendee)) {
            $this->attendees[] = $attendee;
        }

        return $this;
    }

    public function removeAttendee(User $attendee): self
    {
        $this->attendees->removeElement($attendee);
        return $this;
    }

}
