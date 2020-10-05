<?php declare(strict_types=1);


namespace App\Service;


use App\Contracts\Repository\PersonCourseRepositoryInterface;
use App\Contracts\Repository\PersonRepositoryInterface;
use App\Model\Person;

class PersonService
{
    /**
     * @var PersonRepositoryInterface $personRepository
     */
    private $personRepository;

    /**
     * @var PersonCourseRepositoryInterface $personCourseRepository
     */
    private $personCourseRepository;

    /**
     * PersonService constructor.
     * @param PersonRepositoryInterface $personRepository
     * @param PersonCourseRepositoryInterface $personCourseRepository
     */
    public function __construct(PersonRepositoryInterface $personRepository, PersonCourseRepositoryInterface $personCourseRepository)
    {
        $this->personRepository = $personRepository;
        $this->personCourseRepository = $personCourseRepository;
    }

    public function addPerson(string $name, array $courses): Person
    {
        $person = new Person();
        $person->setName($name);
        $result = $this->personRepository->savePerson($person);

        foreach ($courses as $course) {
            $this->personCourseRepository->save($result->getId(), intval($course));
        }

        return $person;
    }

    public function getPerson(int $id): ?Person
    {
        $person = $this->personRepository->getPerson($id);
        if ($person === null) {
            return null;
        }

        $courses = $this->getCoursesPerPerson($person);
        $person->setCourses($courses);

        return $person;
    }

    public function getAll(): array
    {
        return $this->personRepository->getAll();
    }

    public function getCoursesPerPerson(Person $person): array
    {
        return $this->personCourseRepository->getCoursesPerPerson($person->getId());
    }

    public function updatePerson(int $id, string $name, array $courses): ?Person
    {
        $person = $this->personRepository->getPerson($id);
        if ($person === null) {
            return null;
        }

        $person->setName($name);
        $this->personRepository->updatePerson($person);
        $this->personCourseRepository->delete($id);
        foreach ($courses as $course) {
            $this->personCourseRepository->save($id, intval($course));
        }

        return $person;
    }
}