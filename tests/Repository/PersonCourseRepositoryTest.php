<?php declare(strict_types=1);


namespace Tests\Service;

use App\Contracts\Provider\CommandProviderInterface;
use App\Model\Course;
use App\Model\Person;
use App\Repository\PersonCourseRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PersonCourseRepositoryTest extends TestCase
{
    /** @var MockObject $commandProvider */
    private $commandProvider;

    /**
     * @var PersonCourseRepository $personCourseRepository
     */
    private $personCourseRepository;

    protected function setUp(): void
    {
        $this->commandProvider = $this
            ->getMockBuilder(CommandProviderInterface::class)
            ->getMock();

        $this->personCourseRepository = new PersonCourseRepository(
            $this->commandProvider
        );

        parent::setUp();
    }

    /**
     * @test
     */
    public function getStudentsPerCourse_WithCourseId_ShouldReturnListOfPerson(): void
    {
        $sql = 'SELECT 
                    pc.course_id AS courseId,
                    co.name AS courseName,
                    pe.name AS personName, 
                    pe.id AS personId 
                FROM person AS pe
                    INNER JOIN person_course AS pc ON pe.id = pc.person_id
                    INNER JOIN course AS co ON pc.course_id = co.id
                    WHERE pc.course_id = ?';

        $courseIdExpected = 1;
        $values = [ $courseIdExpected ];

        $this->commandProvider
            ->expects($this->once())
            ->method('getAll')
            ->with($sql, $values)
            ->willReturn(
                [
                    0 => [ 'courseId' => $courseIdExpected, 'courseName' => '', 'personId' => $courseIdExpected, 'personName' => '' ]
                ]
            )
        ;

        $list = $this->personCourseRepository->getStudentsPerCourse($courseIdExpected);
        foreach ($list as $item) {
            $this->assertInstanceOf(Person::class, $item);
        }
    }

    /**
     * @test
     */
    public function getCoursesPerPerson_WithPersonId_ShouldReturnListOfCourses(): void
    {
        $sql = 'SELECT 
                    pc.course_id AS courseId,
                    co.name AS courseName,
                    pe.name AS personName, 
                    pe.id AS personId 
                FROM person AS pe
                    INNER JOIN person_course AS pc ON pe.id = pc.person_id
                    INNER JOIN course AS co ON pc.course_id = co.id
                    WHERE pc.person_id = ?';

        $personIdExpected = 1;
        $values = [ $personIdExpected ];

        $this->commandProvider
            ->expects($this->once())
            ->method('getAll')
            ->with($sql, $values)
            ->willReturn(
                [
                    0 => [ 'courseId' => $personIdExpected, 'courseName' => '', 'personId' => $personIdExpected, 'personName' => '' ]
                ]
            )
        ;

        $list = $this->personCourseRepository->getCoursesPerPerson($personIdExpected);
        foreach ($list as $item) {
            $this->assertInstanceOf(Course::class, $item);
        }
    }


    /**
     * @test
     */
    public function save_WithPersonAndCourseId_ShouldReturnListOfCourses(): void
    {
        $sql = 'INSERT INTO person_course (person_id, course_id) VALUES (?, ?)';

        $personIdExpected = 1;
        $courseIdExpected = 1;
        $values = [ $personIdExpected, $courseIdExpected ];

        $pdoStatement = $this->getMockBuilder(\PDOStatement::class)
            ->getMock();

        $this->commandProvider
            ->expects($this->once())
            ->method('executeCommand')
            ->with($sql, $values)
            ->willReturn($pdoStatement)
        ;

        $this->personCourseRepository->save($personIdExpected, $courseIdExpected);
    }

    /**
     * @test
     */
    public function delete_WithPersonId_ShouldReturnListOfCourses(): void
    {
        $sql = 'DELETE FROM person_course WHERE person_id = ?';
        $personId = 1;

        $values = [ $personId ];

        $pdoStatement = $this->getMockBuilder(\PDOStatement::class)
            ->getMock();

        $this->commandProvider
            ->expects($this->once())
            ->method('executeCommand')
            ->with($sql, $values)
            ->willReturn($pdoStatement)
        ;

        $this->personCourseRepository->delete($personId);
    }
}