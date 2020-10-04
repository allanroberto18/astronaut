<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Astronaut;

class AstronautService
{
    public function makeAstronaut(string $name, float $weight): Astronaut
    {
        return new Astronaut($name, $weight);
    }

    public function addWeightToAstronaut(Astronaut $astronaut, float $pounds): void
    {
        $newWeight = $astronaut->getWeight() + $pounds;
        $astronaut->setWeight($newWeight);
    }

    public function launchAstronaut(Astronaut $astronaut): void
    {
        if ($astronaut->getWeight() > 200) {
            echo "{$astronaut->getName()} too heavy, grounded.";
            return;
        }

        echo "{$astronaut->getName()} going where no human has gone before.";
    }
}