<?php declare(strict_types=1);

namespace App\Contracts\Repository;

use App\Model\Astronaut;

interface AstronautRepositoryInterface
{
    public function saveAstronaut(Astronaut $astronaut): Astronaut;
    public function getAll(): array;
}