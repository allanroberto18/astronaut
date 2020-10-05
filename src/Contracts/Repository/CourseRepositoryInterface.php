<?php declare(strict_types=1);

namespace App\Contracts\Repository;

use App\Model\Course;

interface CourseRepositoryInterface
{
    public function saveCourse(Course $course): Course;
    public function getCourse(int $id): ?Course;
    public function getAll(): array;
}