<?php declare(strict_types=1);

namespace App\Service;

use App\Contracts\Repository\CourseRepositoryInterface;
use App\Model\Course;

class CourseService
{
    /** @var CourseRepositoryInterface $courseRepository */
    private $courseRepository;

    /**
     * CourseService constructor.
     * @param CourseRepositoryInterface $courseRepository
     */
    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function addCourse(string $name): Course
    {
        $course = new Course();
        $course->setName($name);

        return $this->courseRepository->saveCourse($course);
    }

    public function getCourse(int $id): ?Course
    {
        return $this->courseRepository->getCourse($id);
    }

    public function getAll(): array
    {
        return $this->courseRepository->getAll();
    }

}