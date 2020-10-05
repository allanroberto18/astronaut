<?php declare(strict_types=1);

namespace App\Repository;

use App\Model\Astronaut;
use App\Contracts\Provider\CommandProviderInterface;
use App\Contracts\Repository\AstronautRepositoryInterface;

class AstronautRepository implements AstronautRepositoryInterface
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

    public function saveAstronaut(Astronaut $astronaut): Astronaut
    {
        $sql = 'INSERT INTO nasa (name, weight) VALUES (?, ?)';
        $values = [ $astronaut->getName(), $astronaut->getWeight() ];
        $astronaut->setId($this->commandProvider->getId($sql, $values));

        return $astronaut;
    }

    public function getAll(): array
    {
        $sql = 'SELECT * FROM nasa ORDER BY id ASC';
        $data = $this->commandProvider->getAll($sql, []);
        $result = [];
        foreach ($data as $item) {
            $astronaut = new Astronaut();
            $astronaut->setId(intval($item['id']));
            $astronaut->setName($item['name']);
            $astronaut->setWeight(floatval($item['weight']));
            $result[] = $astronaut;
        }

        return $result;
    }
}