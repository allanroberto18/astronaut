<?php declare(strict_types=1);


namespace App\Service;


use App\Contracts\Repository\PersonRepositoryInterface;
use App\Model\Person;

class PersonService
{
    /**
     * @var PersonRepositoryInterface $personRepository
     */
    private $personRepository;

    /**
     * PersonService constructor.
     * @param PersonRepositoryInterface $personRepository
     */
    public function __construct(PersonRepositoryInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function addPerson(string $name): Person
    {
        $person = new Person();
        $person->setName($name);
        return $this->personRepository->savePerson($person);
    }

    public function getPerson(int $id): ?Person
    {
        return $this->personRepository->getPerson($id);
    }

    public function getAll(): array
    {
        return $this->personRepository->getAll();
    }

}