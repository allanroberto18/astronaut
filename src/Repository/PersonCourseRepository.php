<?php declare(strict_types=1);

namespace App\Repository;

use App\Contracts\Provider\CommandProviderInterface;
use App\Contracts\Repository\PersonCourseRepositoryInterface;
use App\Model\Course;
use App\Model\Person;

class PersonCourseRepository implements PersonCourseRepositoryInterface
{
    /** @var CommandProviderInterface $commandProvider */
    private $commandProvider;

    /**
     * PersonCourseRepository constructor.
     * @param CommandProviderInterface $commandProvider
     */
    public function __construct(CommandProviderInterface $commandProvider)
    {
        $this->commandProvider = $commandProvider;
    }

    public function getStudentsPerCourse(int $courseId): array
    {
        $sql = 'SELECT 
                    pc.course_id AS courseId,
                    co.name AS courseName,
                    pe.name AS personName, 
                    pe.id AS personId 
                FROM person AS pe
                    INNER JOIN person_course AS pc ON pe.id = pc.person_id
                    INNER JOIN course AS co ON pc.course_id = co.id
                    WHERE pc.course_id = ?';

        $values = [ $courseId ];

        $data = $this->commandProvider->getAll($sql, $values);
        $result = [];
        foreach ($data as $item) {
            $person = new Person();
            $person->setId(intval($item['personId']));
            $person->setName($item['personName']);

            $result[] = $person;
        }

        return $result;
    }

    public function getCoursesPerPerson(int $personId): array
    {
        $sql = 'SELECT 
                    pc.course_id AS courseId,
                    co.name AS courseName,
                    pe.name AS personName, 
                    pe.id AS personId 
                FROM person AS pe
                    INNER JOIN person_course AS pc ON pe.id = pc.person_id
                    INNER JOIN course AS co ON pc.course_id = co.id
                    WHERE pc.person_id = ?';

        $values = [ $personId ];

        $data = $this->commandProvider->getAll($sql, $values);
        $result = [];
        foreach ($data as $item) {
            $course = new Course();
            $course->setId(intval($item['courseId']));
            $course->setName($item['courseName']);

            $result[] = $course;
        }

        return $result;
    }

    public function save(int $personId, int $courseId): void
    {
        $sql = 'INSERT INTO person_course (person_id, course_id) VALUES (?, ?)';
        $values = [ $personId, $courseId ];

        $this->commandProvider->executeCommand($sql, $values);
    }

    public function delete(int $personId): void
    {
        $sql = 'DELETE FROM person_course WHERE person_id = ?';
        $values = [ $personId ];

        $this->commandProvider->executeCommand($sql, $values);
    }
}