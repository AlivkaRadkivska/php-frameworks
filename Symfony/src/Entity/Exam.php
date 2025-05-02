<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 *
 */
#[ORM\Entity]
class Exam implements JsonSerializable
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
    private ?string $title = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $duration = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    private ?int $maxGrade = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $startDate = null;

    /**
     * @var Course|null
     */
    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'exams')]
    private ?Course $course = null;

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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $t
     * @return $this
     */
    public function setTitle(string $t): static
    {
        $this->title = $t;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $t
     * @return $this
     */
    public function setType(string $t): static
    {
        $this->type = $t;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDuration(): ?string
    {
        return $this->duration;
    }

    /**
     * @param string $d
     * @return $this
     */
    public function setDuration(string $d): static
    {
        $this->duration = $d;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxGrade(): ?int
    {
        return $this->maxGrade;
    }

    /**
     * @param int $m
     * @return $this
     */
    public function setMaxGrade(int $m): static
    {
        $this->maxGrade = $m;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * @param \DateTimeInterface $s
     * @return $this
     */
    public function setStartDate(\DateTimeInterface $s): static
    {
        $this->startDate = $s;
        return $this;
    }

    /**
     * @return Course|null
     */
    public function getCourse(): ?Course
    {
        return $this->course;
    }

    /**
     * @param Course $c
     * @return $this
     */
    public function setCourse(Course $c): static
    {
        $this->course = $c;
        return $this;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'duration' => $this->duration,
            'maxGrade' => $this->maxGrade,
            'startDate' => $this->startDate?->format('Y-m-d H:i'),
            "course" => [
                "id" => $this->course?->getId(),
                "name" => $this->course?->getName(),
            ],
        ];
    }
}