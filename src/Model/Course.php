<?php declare(strict_types=1);

namespace App\Model;

class Course
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var array $students
     */
    private $students;

    /**
     * Course constructor.
     */
    public function __construct()
    {
        $this->students = [];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getStudents(): array
    {
        return $this->students;
    }

    /**
     * @param array $students
     */
    public function setStudents(array $students): void
    {
        $this->students = $students;
    }
}