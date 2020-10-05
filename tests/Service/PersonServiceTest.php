<?php declare(strict_types=1);


namespace Tests\Service;


use App\Contracts\Repository\PersonCourseRepositoryInterface;
use App\Contracts\Repository\PersonRepositoryInterface;
use App\Model\Course;
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
     * @var MockObject $personCourseRepository
     */
    private $personCourseRepository;

    /**
     * @var PersonService $personService
     */
    private $personService;

    protected function setUp(): void
    {
        $this->personRepository = $this
            ->getMockBuilder(PersonRepositoryInterface::class)
            ->getMock();

        $this->personCourseRepository = $this
            ->getMockBuilder(PersonCourseRepositoryInterface::class)
            ->getMock();

        $this->personService = new PersonService(
            $this->personRepository,
            $this->personCourseRepository
        );

        parent::setUp();
    }

    /**
     * @test
     */
    public function addPerson_withNameArgument_ShouldReturnPerson(): void
    {
        $id = 1;
        $name = 'Person 1';
        $person = new Person();
        $person->setName($name);

        $personExpected = new Person();
        $personExpected->setId($id);
        $personExpected->setName($name);

        $courses = [ 0 => '1'];

        $this->personRepository
            ->expects($this->once())
            ->method('savePerson')
            ->with($person)
            ->willReturn($personExpected);

        $this->personCourseRepository
            ->expects($this->once())
            ->method('save')
            ->with($personExpected->getId(), intval($courses[0]))
        ;

        $person = $this->personService->addPerson($name, $courses);

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

        $this->personCourseRepository
            ->expects($this->any())
            ->method('getCoursesPerPerson')
            ->with($personExpected->getId())
            ->willReturn(
                [ new Course() ]
            );

        $person = $this->personService->getPerson($id);

        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals($name, $person->getName());
        $this->assertEquals($id, $person->getId());
        $this->assertTrue(is_array($person->getCourses()));
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

    /**
     * @test
     */
    public function getCoursesPerPerson_WithPersonId_ShouldReturnListOfCourses() {
        $personId = 1;
        $personName = 'Person';

        $person = new Person();
        $person->setId($personId);
        $person->setName($personName);

        $this->personCourseRepository
            ->expects($this->any())
            ->method('getCoursesPerPerson')
            ->with($person->getId())
            ->willReturn(
                [ new Course() ]
            );

        $result = $this->personService->getCoursesPerPerson($person);
        foreach ($result as $item) {
            $this->assertInstanceOf(Course::class, $item);
        }
    }

    /**
     * @test
     */
    public function updatePerson_WithPersonIdNameAndCourses_ShouldReturnPerson(): void
    {
        $id = 1;
        $name = 'Person';
        $nameToUpdate = 'Person Updated';

        $courses = [
            0 => '1'
        ];

        $person = new Person();
        $person->setId($id);
        $person->setName($name);

        $this->personRepository
            ->expects($this->once())
            ->method('getPerson')
            ->with($id)
            ->willReturn($person);

        $this->personCourseRepository
            ->expects($this->once())
            ->method('delete')
            ->with($id)
        ;

        $this->personCourseRepository
            ->expects($this->once())
            ->method('save')
            ->with($id, intval($courses[0]));

        $result = $this->personService->updatePerson($id, $nameToUpdate, $courses);
        $this->assertEquals($nameToUpdate, $result->getName());

    }
}