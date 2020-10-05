<?php declare(strict_types=1);

namespace App\Repository;

use App\Contracts\Provider\CommandProviderInterface;
use App\Contracts\Provider\ConnectionProviderInterface;
use App\Contracts\Repository\CourseRepositoryInterface;
use App\Model\Course;

class CourseRepository implements CourseRepositoryInterface
{
    /**
     * @var CommandProviderInterface $commandProvider
     */
    private $commandProvider;

    /**
     * AstronautRepository constructor.
     * @param CommandProviderInterface $commandProvider
     */
    public function __construct(CommandProviderInterface $commandProvider)
    {
        $this->commandProvider = $commandProvider;
    }

    public function saveCourse(Course $course): Course
    {
        $sql = 'INSERT INTO course (name) VALUES (?)';
        $values = [$course->getName()];

        $course->setId($this->commandProvider->getId($sql, $values));

        return $course;
    }

    public function getAll(): array
    {
        $sql = 'SELECT * FROM course ORDER BY id ASC';
        $values = [];
        $data = $this->commandProvider->getAll($sql, $values);
        $result = [];
        foreach ($data as $item) {
            $course = new Course();
            $course->setId(intval($item['id']));
            $course->setName($item['name']);
            $result[] = $course;
        }

        return $result;
    }

    public function getCourse(int $id): ?Course
    {
        $values = [$id];
        $sql = 'SELECT * FROM course WHERE id = ?';
        $data = $this->commandProvider->getById($sql, $values);

        $course = null;
        if (is_array($data) === true && sizeof($data) > 0) {
            $course = new Course();
            $course->setId(intval($data['id']));
            $course->setName($data['name']);
        }

        return $course;
    }
}