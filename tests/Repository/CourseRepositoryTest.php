<?php declare(strict_types=1);

namespace Tests\Repository;

use App\Contracts\Provider\CommandProviderInterface;
use App\Model\Course;
use App\Repository\CourseRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CourseRepositoryTest extends TestCase
{
    /**
     * @var MockObject $pdoStatement
     */
    private $commandProvider;

    /**(
     * @var CourseRepository $courseRepository
     */
    private $courseRepository;

    protected function setUp(): void
    {
        $this->commandProvider = $this->getMockBuilder(CommandProviderInterface::class)
            ->getMock();

        $this->courseRepository = new CourseRepository($this->commandProvider);

        parent::setUp();
    }

    /**
     * @test
     */
    public function saveCourse_WithPersonObj_ShouldReturnObj(): void
    {
        $idExpected = 1;
        $course = new Course();
        $course->setName('Course 1');
        $sql = 'INSERT INTO course (name) VALUES (?)';
        $values = [$course->getName()];

        $this->commandProvider
            ->expects($this->once())
            ->method('getId')
            ->with($sql, $values)
            ->willReturn($idExpected);

        $result = $this->courseRepository->saveCourse($course);

        $this->assertEquals($idExpected, $result->getId());
    }

    /**
     * @test
     */
    public function getAll_WithNoArguments_MustReturnListOfCourses(): void
    {
        $sql = 'SELECT * FROM course ORDER BY id ASC';
        $values = [];

        $this->commandProvider
            ->expects($this->once())
            ->method('getAll')
            ->with($sql, $values)
            ->willReturn(
                [
                    0 => ['id' => 1, 'name' => 'Course 1'],
                    1 => ['id' => 2, 'name' => 'Course 2'],
                    2 => ['id' => 3, 'name' => 'Course 3'],
                    3 => ['id' => 4, 'name' => 'Course 4'],
                    4 => ['id' => 5, 'name' => 'Course 5']
                ]
            );

        $result = $this->courseRepository->getAll();
        foreach ($result as $item) {
            $this->assertInstanceOf(Course::class, $item);
        }
    }

    /**
     * @test
     */
    public function getById_WithNoArguments_MustReturnCourse(): void
    {
        $idExpected = 1;
        $courseExpected = 'Course 1';
        $sql = 'SELECT * FROM course WHERE id = ?';
        $values = [ $idExpected ];

        $this->commandProvider
            ->expects($this->once())
            ->method('getById')
            ->with($sql, $values)
            ->willReturn(['id' => 1, 'name' => '' . $courseExpected . '']);

        $course = $this->courseRepository->getCourse($idExpected);

        $this->assertInstanceOf(Course::class, $course);
        $this->assertEquals($idExpected, $course->getId());
        $this->assertEquals($courseExpected, $course->getName());
    }
}