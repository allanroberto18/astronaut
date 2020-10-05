<?php declare(strict_types=1);

namespace Tests\Repository;

use App\Contracts\Provider\CommandProviderInterface;
use App\Model\Person;
use App\Repository\PersonRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PersonRepositoryTest extends TestCase
{
    /**
     * @var MockObject $pdoStatement
     */
    private $commandProvider;

    /**(
     * @var PersonRepository $personRepository
     */
    private $personRepository;

    protected function setUp(): void
    {
        $this->commandProvider = $this->getMockBuilder(CommandProviderInterface::class)
            ->getMock();

        $this->personRepository = new PersonRepository($this->commandProvider);

        parent::setUp();
    }

    /**
     * @test
     */
    public function savePerson_WithPersonObj_ShouldReturnObj(): void
    {
        $idExpected = 1;
        $person = new Person();
        $person->setName('Person 1');
        $sql = 'INSERT INTO person (name) VALUES (?)';
        $values = [$person->getName()];

        $this->commandProvider
            ->expects($this->once())
            ->method('getId')
            ->with($sql, $values)
            ->willReturn($idExpected);

        $result = $this->personRepository->savePerson($person);

        $this->assertEquals($idExpected, $result->getId());
    }

    /**
     * @test
     */
    public function getAll_WithNoArguments_MustReturnListOfPersons(): void
    {
        $sql = 'SELECT * FROM person ORDER BY id ASC';
        $values = [];

        $this->commandProvider
            ->expects($this->once())
            ->method('getAll')
            ->with($sql, $values)
            ->willReturn(
                [
                    0 => ['id' => 1, 'name' => 'Person 1'],
                    1 => ['id' => 2, 'name' => 'Person 2'],
                    2 => ['id' => 3, 'name' => 'Person 3'],
                    3 => ['id' => 4, 'name' => 'Person 4'],
                    4 => ['id' => 5, 'name' => 'Person 5']
                ]
            );

        $result = $this->personRepository->getAll();
        foreach ($result as $item) {
            $this->assertInstanceOf(Person::class, $item);
        }
    }

    /**
     * @test
     */
    public function getById_WithNoArguments_MustReturnPerson(): void
    {
        $idExpected = 1;
        $personExpected = 'Person 1';
        $sql = 'SELECT * FROM person WHERE id = ?';
        $values = [$idExpected];

        $this->commandProvider
            ->expects($this->once())
            ->method('getById')
            ->with($sql, $values)
            ->willReturn(['id' => 1, 'name' => $personExpected]);

        $person = $this->personRepository->getPerson($idExpected);

        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals($idExpected, $person->getId());
        $this->assertEquals($personExpected, $person->getName());
    }

    /**
     * @test
     */
    public function updatePerson_WithPersonIdNameAndCourses_ShouldReturnPerson(): void
    {
        $id = 1;
        $name = 'Person';

        $person = new Person();
        $person->setId($id);
        $person->setName($name);

        $sql = 'UPDATE person SET name = ? WHERE id = ?';
        $values = [$name, $id];

        $this->commandProvider
            ->expects($this->once())
            ->method('executeCommand')
            ->with($sql, $values)
            ->willReturn(
                $this
                    ->getMockBuilder(\PDOStatement::class)
                    ->getMock()
            );

        $this->personRepository->updatePerson($person);
    }
}