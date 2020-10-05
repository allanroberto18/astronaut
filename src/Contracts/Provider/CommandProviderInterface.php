<?php declare(strict_types=1);

namespace App\Contracts\Provider;

use PDOStatement;

interface CommandProviderInterface
{
    /**
     * @param string $sql
     * @param array $values
     * @return array
     */
    public function getAll(string $sql, array $values) :array;

    /**
     * @param string $sql
     * @param array $values
     * @return array
     */
    public function getById(string $sql, array $values) :array;

    /**
     * @param string $sql
     * @param array $values
     * @return int
     */
    public function getId(string $sql, array $values): int;

    /**
     * @param string $sql
     * @param array $values
     * @return PDOStatement
     */
    public function executeCommand(string $sql, array $values): PDOStatement;
}