<?php declare(strict_types=1);


namespace App\Contracts\Repository;


interface PersonCourseRepositoryInterface
{
    public function getStudentsPerCourse(int $courseId): array;
    public function getCoursesPerPerson(int $personId): array;
    public function save(int $personId, int $courseId): void;
    public function delete(int $personId): void;
}