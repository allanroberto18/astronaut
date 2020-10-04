<?php declare(strict_types=1);

namespace Tests\Service;

use App\Contracts\Repository\AstronautRepositoryInterface;
use App\Model\Astronaut;
use App\Service\AstronautService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AstronautServiceTest extends TestCase
{
    /**
     * @var MockObject $astronautRepository
     */
    private $astronautRepository;

    /**
     * @var AstronautService $astronautService
     */
    private $astronautService;

    protected function setUp(): void
    {
        $this->astronautRepository = $this->getMockBuilder(AstronautRepositoryInterface::class)
            ->getMock();

        $this->astronautService = new AstronautService($this->astronautRepository);
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
     * @test
     */
    public function save_withNameAndWeight_ShouldReturnObj(): void
    {
        $id = 1;
        $name = 'Astronaut';
        $weight = 10.0;

        $astronaut = new Astronaut();
        $astronaut->setId($id);
        $astronaut->setName($name);
        $astronaut->setWeight($weight);

        $this->astronautRepository
            ->expects($this->once())
            ->method('saveAstronaut')
            ->willReturn($astronaut);

        $result = $this->astronautService->save($name, $weight);

        $this->assertEquals($astronaut->getId(), $result->getId());
    }

    /**
     * @test
     */
    public function getAll_WithNoArguments_ShouldReturnListOfObj(): void
    {
        $id = 1;
        $name = 'Astronaut';
        $weight = 10.0;

        $astronaut = new Astronaut();
        $astronaut->setId($id);
        $astronaut->setName($name);
        $astronaut->setWeight($weight);

        $this->astronautRepository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn(
                [
                    0 => $astronaut
                ]
            );

        $result = $this->astronautService->getAll();

        $totalExpected = 1;
        $this->assertEquals($totalExpected, sizeof($result));
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
