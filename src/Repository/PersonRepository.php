<?php declare(strict_types=1);

namespace App\Repository;

use App\Contracts\Provider\CommandProviderInterface;
use App\Contracts\Repository\PersonRepositoryInterface;
use App\Model\Person;

class PersonRepository implements PersonRepositoryInterface
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

    public function savePerson(Person $person): Person
    {
        $values = [$person->getName()];
        $sql = 'INSERT INTO person (name) VALUES (?)';

        $person->setId($this->commandProvider->getId($sql, $values));

        return $person;
    }

    public function getAll(): array
    {
        $values = [];
        $sql = 'SELECT * FROM person ORDER BY id ASC';
        $data = $this->commandProvider->getAll($sql, $values);
        $result = [];
        foreach ($data as $item) {
            $person = new Person();
            $person->setId(intval($item['id']));
            $person->setName($item['name']);
            $result[] = $person;
        }

        return $result;
    }

    public function getPerson(int $id): ?Person
    {
        $sql = 'SELECT * FROM person WHERE id = ?';
        $values = [$id];
        $data = $this->commandProvider->getById($sql, $values);

        $person = null;
        if (is_array($data) === true && sizeof($data) > 0) {
            $person = new Person();
            $person->setId(intval($data['id']));
            $person->setName($data['name']);
        }

        return $person;
    }

    public function updatePerson(Person $person): void
    {
        $sql = 'UPDATE person SET name = ? WHERE id = ?';
        $values = [ $person->getName(), $person->getId() ];

        $this->commandProvider->executeCommand($sql, $values);
    }
}