<?php


namespace App\Repository;


use App\Model\Astronaut;
use App\Contracts\Provider\ConnectionProviderInterface;
use App\Contracts\Repository\AstronautRepositoryInterface;

class AstronautRepository implements AstronautRepositoryInterface
{
    /**
     * @var ConnectionProviderInterface $connectionProvider
     */
    private $connectionProvider;

    /**
     * AstronautRepository constructor.
     * @param ConnectionProviderInterface $connectionProvider
     */
    public function __construct(ConnectionProviderInterface $connectionProvider)
    {
        $this->connectionProvider = $connectionProvider;
    }

    public function saveAstronaut(Astronaut $astronaut): Astronaut
    {
        $pdo = $this->connectionProvider->getConnection();
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('INSERT INTO nasa (name, weight) VALUES (?, ?)');
        $stmt->execute([$astronaut->getName(), $astronaut->getWeight()]);
        $pdo->commit();

        $astronaut->setId(intval($pdo->lastInsertId()));

        return $astronaut;
    }

    public function getAll(): array
    {
        $pdo = $this->connectionProvider->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM nasa ORDER BY id ASC');
        $stmt->execute([]);
        $data = $stmt->fetchAll();
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