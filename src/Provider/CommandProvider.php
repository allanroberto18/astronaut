<?php declare(strict_types=1);

namespace App\Provider;

use PDOStatement;
use App\Contracts\Provider\CommandProviderInterface;
use App\Contracts\Provider\ConnectionProviderInterface;

class CommandProvider implements CommandProviderInterface
{
    /**
     * @var ConnectionProviderInterface $connectionProvider
     */
    private $connectionProvider;

    /**
     * CommandProvider constructor.
     * @param ConnectionProviderInterface $connectionProvider
     */
    public function __construct(ConnectionProviderInterface $connectionProvider)
    {
        $this->connectionProvider = $connectionProvider;
    }

    /**
     * @param string $sql
     * @param array $values
     * @return array
     */
    public function getAll(string $sql, array $values) :array
    {
        $data = $this
            ->executeCommand($sql, $values)
            ->fetchAll();

        if (is_bool($data)) {
            return [];
        }

        return $data;
    }

    /**
     * @param string $sql
     * @param array $values
     * @return array
     */
    public function getById(string $sql, array $values) :array
    {
        $data = $this
            ->executeCommand($sql, $values)
            ->fetch(\PDO::FETCH_ASSOC);

        if (is_bool($data)) {
            return [];
        }

        return $data;
    }

    /**
     * @param string $sql
     * @param array $values
     * @return int
     */
    public function getId(string $sql, array $values): int
    {
        $pdo = $this->connectionProvider->getConnection();
        $pdo->beginTransaction();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        $id = intval($pdo->lastInsertId());
        $pdo->commit();

        return $id;
    }

    /**
     * @param string $sql
     * @param array $values
     * @return PDOStatement
     */
    public function executeCommand(string $sql, array $values): PDOStatement
    {
        $pdo = $this->connectionProvider->getConnection();
        $pdo->beginTransaction();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        $pdo->commit();

        return $stmt;
    }
}