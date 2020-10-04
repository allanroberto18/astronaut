<?php declare(strict_types=1);

namespace App\Contracts\Provider;

use PDO;

interface ConnectionProviderInterface
{
    public function getConnection(): PDO;

}