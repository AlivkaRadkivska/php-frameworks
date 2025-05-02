<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 *
 */
#[ORM\Entity]
#[ORM\Table(name: '`group`')]
class Group implements JsonSerializable
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
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $major = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    private ?int $year = null;

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
     * @param string $n
     * @return $this
     */
    public function setName(string $n): static
    {
        $this->name = $n;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMajor(): ?string
    {
        return $this->major;
    }

    /**
     * @param string $m
     * @return $this
     */
    public function setMajor(string $m): static
    {
        $this->major = $m;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * @param int $y
     * @return $this
     */
    public function setYear(int $y): static
    {
        $this->year = $y;
        return $this;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'major' => $this->major,
            'year' => $this->year
        ];
    }
}

