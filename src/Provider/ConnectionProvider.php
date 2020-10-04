<?php declare(strict_types=1);

namespace App\Provider;

use PDO;
use App\Contracts\Provider\ConnectionProviderInterface;

class ConnectionProvider implements ConnectionProviderInterface
{
    /**
     * @var string $dbHost
     */
    private $dbHost = '127.0.0.1';

    /**
     * @var int $dbPort
     */
    private $dbPort = 3306;

    /**
     * @var string $dbName
     */
    private $dbName = 'nasa';

    /**
     * @var string $dbUser
     */
    private $dbUser = 'nasa';

    /**
     * @var string $dbPassword
     */
    private $dbPassword = 'nasa';

    /**
     * @var PDO|null $pdo
     */
    private $pdo;

    /**
     * PDOProvider constructor.
     */
    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=$this->dbHost;port=$this->dbPort;dbname=$this->dbUser", $this->dbName, $this->dbPassword);
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}