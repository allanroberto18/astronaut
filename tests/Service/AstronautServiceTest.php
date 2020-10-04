<?php declare(strict_types=1);

namespace Tests\Service;

use App\Exception\WeightException;
use App\Model\Astronaut;
use App\Service\AstronautService;
use PHPUnit\Framework\TestCase;

class AstronautServiceTest extends TestCase
{
    /**
     * @var AstronautService $astronautService
     */
    private $astronautService;

    protected function setUp(): void
    {
        $this->astronautService = new AstronautService();
        parent::setUp();
    }

    /**
     * @test
     */
    public function makeAstronaut_withNameAndWeight_MustReturnAnAstronaut(): void
    {
        $name = 'Astronaut';
        $weight = 50.0;
        $astronaut = $this->buildAstronaut($name, $weight);
        $this->assertInstanceOf(Astronaut::class, $astronaut);
        $this->assertEquals($name, $astronaut->getName());
        $this->assertEquals($weight, $astronaut->getWeight());
    }

    /**
     * @test
     */
    public function addWeightToAstronaut_withAstronautObjectAndWeight_WeightShouldIncrease(): void
    {
        $name = 'Astronaut';
        $weight = 50.0;
        $pounds = 20.0;
        $weightExpected = $weight + $pounds;
        $astronaut = $this->buildAstronaut($name, $weight);
        $this->astronautService->addWeightToAstronaut($astronaut, $pounds);
        $this->assertEquals($weightExpected, $astronaut->getWeight());
    }

    /**
     * @test
     */
    public function launchAstronaut_WhenWeightIsGreaterThan200_MustPrintMessage(): void
    {
        $astronaut = $this->buildAstronaut('Astronaut', 201);
        $this->astronautService->launchAstronaut($astronaut);
        $msgExpected = "{$astronaut->getName()} too heavy, grounded.";
        $this->expectOutputString($msgExpected);
    }

    /**
     * @test
     */
    public function launchAstronaut_WhenWeightIsLessThan200_MustPrintMessage(): void
    {
        $astronaut = $this->buildAstronaut('Astronaut', 200);
        $this->astronautService->launchAstronaut($astronaut);
        $msgExpected = "{$astronaut->getName()} going where no human has gone before.";
        $this->expectOutputString($msgExpected);
    }

    /**
     * @param string $name
     * @param float $weight
     * @return Astronaut
     */
    private function buildAstronaut(string $name, float $weight): Astronaut
    {
        return $this->astronautService->makeAstronaut($name, $weight);
    }
}
