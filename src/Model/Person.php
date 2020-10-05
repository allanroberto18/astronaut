<?php declare(strict_types=1);

namespace App\Model;

class Person
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
     * @var array $courses
     */
    private $courses;

    /**
     * Person constructor.
     */
    public function __construct()
    {
        $this->courses = [];
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
    public function getCourses(): array
    {
        return $this->courses;
    }

    /**
     * @param array $courses
     */
    public function setCourses(array $courses): void
    {
        $this->courses = $courses;
    }
}