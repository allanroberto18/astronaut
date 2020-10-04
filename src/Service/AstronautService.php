<?php declare(strict_types=1);

namespace App\Service;

use App\Contracts\Repository\AstronautRepositoryInterface;
use App\Model\Astronaut;

class AstronautService
{
    /**
     * @var AstronautRepositoryInterface $astronautRepository
     */
    private $astronautRepository;

    /**
     * AstronautService constructor.
     * @param AstronautRepositoryInterface $astronautRepository
     */
    public function __construct(AstronautRepositoryInterface $astronautRepository)
    {
        $this->astronautRepository = $astronautRepository;
    }

    /**
     * @param string $name
     * @param float $weight
     * @return Astronaut
     */
    public function makeAstronaut(string $name, float $weight): Astronaut
    {
        $astronaut = new Astronaut();
        $astronaut->setName($name);
        $astronaut->setWeight($weight);

        return $astronaut;
    }

    /**
     * @param Astronaut $astronaut
     * @param float $pounds
     */
    public function addWeightToAstronaut(Astronaut $astronaut, float $pounds): void
    {
        $newWeight = $astronaut->getWeight() + $pounds;
        $astronaut->setWeight($newWeight);
    }

    /**
     * @param Astronaut $astronaut
     */
    public function launchAstronaut(Astronaut $astronaut): void
    {
        if ($astronaut->getWeight() > 200) {
            echo "{$astronaut->getName()} too heavy, grounded.";
            return;
        }

        echo "{$astronaut->getName()} going where no human has gone before.";
    }

    /**
     * @param string $name
     * @param float $weight
     * @return Astronaut
     */
    public function save(string $name, float $weight): Astronaut
    {
        $astronaut = new Astronaut();
        $astronaut->setName($name);
        $astronaut->setWeight($weight);

        return $this->astronautRepository->saveAstronaut($astronaut);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->astronautRepository->getAll();
    }
}