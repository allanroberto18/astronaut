<?php declare(strict_types=1);

namespace App\Model;

class Astronaut
{
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var float $weight
     */
    private $weight;

    /**
     * Astronaut constructor.
     * @param string $name
     * @param float $weight
     */
    public function __construct(string $name, float $weight)
    {
        $this->name = $name;
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     * @return void
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }
}