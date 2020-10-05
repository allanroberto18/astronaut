<?php declare(strict_types=1);

namespace App\Contracts\Repository;

use App\Model\Person;

interface PersonRepositoryInterface
{
    public function savePerson(Person $person): Person;
    public function getPerson(int $id): ?Person;
    public function getAll(): array;
    public function updatePerson(Person $person): void;
}