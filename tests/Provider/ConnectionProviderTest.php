<?php declare(strict_types=1);

namespace Tests\Provider;

use PDO;
use PHPUnit\Framework\TestCase;
use App\Provider\ConnectionProvider;

class ConnectionProviderTest extends TestCase
{
    /**
     * @test
     */
    public function connect_withUserAndPassword_MustReturnPDO(): void
    {
        $pdoProvider = new ConnectionProvider();
        $this->assertInstanceOf(PDO::class, $pdoProvider->getConnection());
    }
}