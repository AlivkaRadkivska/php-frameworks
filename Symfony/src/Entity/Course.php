<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use JsonSerializable;

#[ORM\Entity]
class Course implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 1000)]
    private ?string $description = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 10)]
    private ?string $credits = null;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: Exam::class, mappedBy: 'course', cascade: ['persist', 'remove'])]
    private Collection $exams;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: ScheduleEvent::class, mappedBy: 'course', cascade: ['persist', 'remove'])]
    private Collection $scheduleEvents;

    /**
     *
     */
    public function __construct()
    {
        $this->exams = new ArrayCollection();
        $this->scheduleEvents = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $desc
     * @return $this
     */
    public function setDescription(string $desc): static
    {
        $this->description = $desc;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCredits(): ?string
    {
        return $this->credits;
    }

    /**
     * @param string $c
     * @return $this
     */
    public function setCredits(string $c): static
    {
        $this->credits = $c;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExams(): mixed
    {
        return array_map(function ($exam) {
            return [
                'id' => $exam?->getId(),
                'name' => $exam?->getTitle(),
                'startDate' => $exam?->getStartDate(),
            ];
        }, iterator_to_array($this->exams));
    }

    /**
     *
     * @return mixed
     */
    public function getScheduleEvents(): mixed
    {
        return array_map(function ($scheduleEvent) {
            return [
                'id' => $scheduleEvent?->getId(),
                'meetingLink' => $scheduleEvent?->getMeetingLink(),
                'startDate' => $scheduleEvent?->getStartDate()->format('Y-m-d H:i'),
                'endDate' => $scheduleEvent?->getEndDate()->format('Y-m-d H:i'),
            ];
        }, iterator_to_array($this->scheduleEvents));
    }

    /**
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'credits' => $this->credits
        ];
    }
}