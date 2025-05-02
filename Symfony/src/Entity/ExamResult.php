<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 *
 */
#[ORM\Entity]
class ExamResult implements JsonSerializable
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
    private ?string $studentName = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 1000)]
    private ?string $answer = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    private ?int $obtainedGrade = null;

    /**
     * @var Exam|null
     */
    #[ORM\ManyToOne(targetEntity: Exam::class)]
    private ?Exam $exam = null;

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
    public function getStudentName(): ?string
    {
        return $this->studentName;
    }

    /**
     * @param string $n
     * @return $this
     */
    public function setStudentName(string $n): static
    {
        $this->studentName = $n;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    /**
     * @param string $a
     * @return $this
     */
    public function setAnswer(string $a): static
    {
        $this->answer = $a;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getObtainedGrade(): ?int
    {
        return $this->obtainedGrade;
    }

    /**
     * @param int $g
     * @return $this
     */
    public function setObtainedGrade(int $g): static
    {
        $this->obtainedGrade = $g;
        return $this;
    }

    /**
     * @return Exam|null
     */
    public function getExam(): ?Exam
    {
        return $this->exam;
    }

    /**
     * @param Exam $e
     * @return $this
     */
    public function setExam(Exam $e): static
    {
        $this->exam = $e;
        return $this;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'studentName' => $this->studentName,
            'answer' => $this->answer,
            'obtainedGrade' => $this->obtainedGrade,
            "exam" => [
                "id" => $this->exam?->getId(),
                "title" => $this->exam?->getTitle(),
                "startDate" => $this->exam?->getStartDate(),
            ],
        ];
    }
}