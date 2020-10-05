<?php declare(strict_types=1);


namespace Tests\Service;


use App\Contracts\Repository\PersonRepositoryInterface;
use App\Model\Person;
use App\Service\PersonService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PersonServiceTest extends TestCase
{

    /**
     * @var MockObject $personRepository
     */
    private $personRepository;

    /**
     * @var PersonService $personService
     */
    private $personService;

    protected function setUp(): void
    {
        $this->personRepository = $this
            ->getMockBuilder(PersonRepositoryInterface::class)
            ->getMock();

        $this->personService = new PersonService(
            $this->personRepository
        );

        parent::setUp();
    }

    /**
     * @test
     */
    public function addPerson_withNameArgument_ShouldReturnPerson(): void
    {
        $name = 'Person 1';
        $personExpected = new Person();
        $personExpected->setName($name);

        $this->personRepository
            ->expects($this->once())
            ->method('savePerson')
            ->with($personExpected)
            ->willReturn($personExpected);
        
        $person = $this->personService->addPerson($name);

        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals($name, $person->getName());
    }

    /**
     * @test
     */
    public function getPerson_withIdArgument_ShouldReturnPerson(): void
    {
        $id = 1;
        $name = 'Person 1';
        $personExpected = new Person();
        $personExpected->setId($id);
        $personExpected->setName($name);

        $this->personRepository
            ->expects($this->once())
            ->method('getPerson')
            ->with($id)
            ->willReturn($personExpected);

        $person = $this->personService->getPerson($id);

        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals($name, $person->getName());
        $this->assertEquals($id, $person->getId());
    }

    /**
     * @test
     */
    public function getAll_withNoArgument_ShouldReturnListOfPersons(): void
    {
        $personExpected = new Person();
        $personExpected->setId(1);
        $personExpected->setName('Person 1');

        $this->personRepository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn([ $personExpected ]);

        $persons = $this->personService->getAll();

        foreach ($persons as $person) {
            $this->assertInstanceOf(Person::class, $person);
        }
    }
}