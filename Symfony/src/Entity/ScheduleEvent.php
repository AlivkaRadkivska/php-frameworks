<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 *
 */
#[ORM\Entity]
class ScheduleEvent implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $startDate = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $endDate = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $meetingLink = null;

    /**
     * @var Course|null
     */
    #[ORM\ManyToOne(targetEntity: Course::class)]
    private ?Course $course = null;

    /**
     * @var Group|null
     */
    #[ORM\ManyToOne(targetEntity: Group::class)]
    private ?Group $group = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @param \DateTimeInterface $e
     * @return $this
     */
    public function setEndDate(\DateTimeInterface $e): static
    {
        $this->endDate = $e;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMeetingLink(): ?string
    {
        return $this->meetingLink;
    }

    /**
     * @param string $l
     * @return $this
     */
    public function setMeetingLink(string $l): static
    {
        $this->meetingLink = $l;
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
     * @return Group|null
     */
    public function getGroup(): ?Group
    {
        return $this->group;
    }

    /**
     * @param Group $g
     * @return $this
     */
    public function setGroup(Group $g): static
    {
        $this->group = $g;
        return $this;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'meetingLink' => $this->meetingLink,
            'startDate' => $this->startDate?->format('Y-m-d H:i'),
            'endDate' => $this->endDate?->format('Y-m-d H:i'),
            'course' => [
                'id' => $this->course?->getId(),
                'name' => $this->course?->getName(),
            ],
            'group' => [
                'id' => $this->group?->getId(),
                'name' => $this->group?->getName(),
            ],
        ];
    }
}
