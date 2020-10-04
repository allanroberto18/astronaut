<?php declare(strict_types=1);


namespace Tests\Repository;


use PDO;
use PDOStatement;
use App\Model\Astronaut;
use PHPUnit\Framework\TestCase;
use App\Repository\AstronautRepository;
use PHPUnit\Framework\MockObject\MockObject;
use App\Contracts\Provider\ConnectionProviderInterface;

class AstronautRepositoryTest extends TestCase
{
    /**
     * @var MockObject $pdo
     */
    private $pdo;

    /**
     * @var MockObject $connectionProvider ;
     */
    private $connectionProvider;

    /**
     * @var MockObject $pdoStatement
     */
    private $pdoStatement;

    /**(
     * @var AstronautRepository $astronautRepository
     */
    private $astronautRepository;

    protected function setUp(): void
    {
        $this->pdo = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->pdoStatement = $this->getMockBuilder(PDOStatement::class)
            ->getMock();

        $this->connectionProvider = $this->getMockBuilder(ConnectionProviderInterface::class)
            ->getMock();

        $this->astronautRepository = new AstronautRepository($this->connectionProvider);

        parent::setUp();
    }

    /**
     * @test
     */
    public function saveAstronaut_WithAstronautObj_ShouldReturnObj(): void
    {
        $id = "1";

        $astronaut = new Astronaut();
        $astronaut->setName('Astronaut');
        $astronaut->setWeight(10.0);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->with([ $astronaut->getName(), $astronaut->getWeight() ])
            ->willReturn(true);

        $this->pdo
            ->expects($this->once())
            ->method('beginTransaction')
            ->willReturn(true);

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('INSERT INTO nasa (name, weight) VALUES (?, ?)'))
            ->willReturn($this->pdoStatement);

        $this->pdo
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);

        $this->pdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn($id);

        $this->connectionProvider
            ->expects($this->once())
            ->method('getConnection')
            ->willReturn($this->pdo);

        $result = $this->astronautRepository->saveAstronaut($astronaut);
        $idExpected = intval($id);

        $this->assertEquals($idExpected, $result->getId());
    }

    /**
     * @test
     */
    public function getAll_WithNoArguments_MustReturnListOfAstronauts(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->with([])
            ->willReturn(true);

        $this->pdoStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn(
                [
                    0 => [ 'id' =>  1, 'name' => 'Astronaut 1', 'weight' => 10.0 ],
                    1 => [ 'id' =>  2, 'name' => 'Astronaut 2', 'weight' => 20.0 ],
                    2 => [ 'id' =>  3, 'name' => 'Astronaut 3', 'weight' => 30.0 ],
                    3 => [ 'id' =>  4, 'name' => 'Astronaut 4', 'weight' => 40.0 ],
                    4 => [ 'id' =>  5, 'name' => 'Astronaut 5', 'weight' => 50.0 ]
                ]
            );

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM nasa ORDER BY id ASC'))
            ->willReturn($this->pdoStatement);

        $this->connectionProvider
            ->expects($this->once())
            ->method('getConnection')
            ->willReturn($this->pdo);

        $result = $this->astronautRepository->getAll();
        foreach ($result as $item)
        {
            $this->assertInstanceOf(Astronaut::class, $item);
        }
    }
}