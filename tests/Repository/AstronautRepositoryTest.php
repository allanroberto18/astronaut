<?php declare(strict_types=1);

namespace Tests\Repository;

use App\Model\Astronaut;
use App\Contracts\Provider\CommandProviderInterface;
use App\Repository\AstronautRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AstronautRepositoryTest extends TestCase
{
    /**
     * @var MockObject $commandProvider
     */
    private $commandProvider;

    /**(
     * @var AstronautRepository $astronautRepository
     */
    private $astronautRepository;

    protected function setUp(): void
    {
        $this->commandProvider = $this
            ->getMockBuilder(CommandProviderInterface::class)
            ->getMock();

        $this->astronautRepository = new AstronautRepository($this->commandProvider);

        parent::setUp();
    }

    /**
     * @test
     */
    public function saveAstronaut_WithAstronautObj_ShouldReturnObj(): void
    {
        $idExpected = 1;
        $astronaut = new Astronaut();
        $astronaut->setName('Astronaut');
        $astronaut->setWeight(10.0);

        $values = [ $astronaut->getName(), $astronaut->getWeight() ];
        $sql = 'INSERT INTO nasa (name, weight) VALUES (?, ?)';

        $this->commandProvider
            ->expects($this->once())
            ->method('getId')
            ->with($sql, $values)
            ->willReturn($idExpected);

        $result = $this->astronautRepository->saveAstronaut($astronaut);
        $this->assertEquals($idExpected, $result->getId());
    }

    /**
     * @test
     */
    public function getAll_WithNoArguments_MustReturnListOfAstronauts(): void
    {
        $sql = 'SELECT * FROM nasa ORDER BY id ASC';
        $values = [];
        $this->commandProvider
            ->expects($this->once())
            ->method('getAll')
            ->with($sql, $values)
            ->willReturn(
                [
                    0 => [ 'id' =>  1, 'name' => 'Astronaut 1', 'weight' => 10.0 ],
                    1 => [ 'id' =>  2, 'name' => 'Astronaut 2', 'weight' => 20.0 ],
                    2 => [ 'id' =>  3, 'name' => 'Astronaut 3', 'weight' => 30.0 ],
                    3 => [ 'id' =>  4, 'name' => 'Astronaut 4', 'weight' => 40.0 ],
                    4 => [ 'id' =>  5, 'name' => 'Astronaut 5', 'weight' => 50.0 ]
                ]
            );

        $result = $this->astronautRepository->getAll();
        foreach ($result as $item)
        {
            $this->assertInstanceOf(Astronaut::class, $item);
        }
    }
}