<?php declare(strict_types=1);

namespace App\Service;

use App\Contracts\Repository\CourseRepositoryInterface;
use App\Contracts\Repository\PersonCourseRepositoryInterface;
use App\Model\Course;
use App\Model\Person;

class CourseService
{
    /** @var CourseRepositoryInterface $courseRepository */
    private $courseRepository;


    /** @var PersonCourseRepositoryInterface $personCourseRepository */
    private $personCourseRepository;

    /**
     * CourseService constructor.
     * @param CourseRepositoryInterface $courseRepository
     * @param PersonCourseRepositoryInterface $personCourseRepository
     */
    public function __construct(CourseRepositoryInterface $courseRepository, PersonCourseRepositoryInterface $personCourseRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->personCourseRepository = $personCourseRepository;
    }

    public function addCourse(string $name): Course
    {
        $course = new Course();
        $course->setName($name);

        return $this->courseRepository->saveCourse($course);
    }

    public function getCourse(int $id): ?Course
    {
        $course = $this->courseRepository->getCourse($id);
        if ($course === null) {
            return null;
        }

        $students = $this->getStudentsPerCourse($course);
        $course->setStudents($students);
        return $course;
    }

    public function getStudentsPerCourse(Course $course): array
    {
        return $this->personCourseRepository->getStudentsPerCourse($course->getId());
    }

    public function getAll(): array
    {
        return $this->courseRepository->getAll();
    }

}