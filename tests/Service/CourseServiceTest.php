<?php declare(strict_types=1);


namespace Tests\Service;


use App\Contracts\Repository\CourseRepositoryInterface;
use App\Contracts\Repository\PersonCourseRepositoryInterface;
use App\Model\Course;
use App\Model\Person;
use App\Service\CourseService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CourseServiceTest extends TestCase
{

    /**
     * @var MockObject $courseRepository
     */
    private $courseRepository;

    /**
     * @var MockObject $personCourseRepository
     */
    private $personCourseRepository;

    /**
     * @var CourseService $courseService
     */
    private $courseService;

    protected function setUp(): void
    {
        $this->courseRepository = $this
            ->getMockBuilder(CourseRepositoryInterface::class)
            ->getMock();

        $this->personCourseRepository = $this
            ->getMockBuilder(PersonCourseRepositoryInterface::class)
            ->getMock();

        $this->courseService = new CourseService(
            $this->courseRepository,
            $this->personCourseRepository
        );

        parent::setUp();
    }

    /**
     * @test
     */
    public function addCourse_withNameArgument_ShouldReturnCourse(): void
    {
        $name = 'Course 1';
        $courseExpected = new Course();
        $courseExpected->setName($name);

        $this->courseRepository
            ->expects($this->once())
            ->method('saveCourse')
            ->with($courseExpected)
            ->willReturn($courseExpected);
        
        $course = $this->courseService->addCourse($name);

        $this->assertInstanceOf(Course::class, $course);
        $this->assertEquals($name, $course->getName());
    }

    /**
     * @test
     */
    public function getCourse_withIdArgument_ShouldReturnCourse(): void
    {
        $id = 1;
        $name = 'Course 1';
        $courseExpected = new Course();
        $courseExpected->setId($id);
        $courseExpected->setName($name);

        $this->courseRepository
            ->expects($this->once())
            ->method('getCourse')
            ->with($id)
            ->willReturn($courseExpected);

        $this->personCourseRepository
            ->expects($this->any())
            ->method('getStudentsPerCourse')
            ->with($courseExpected->getId())
            ->willReturn(
                [ new Person() ]
            );

        $course = $this->courseService->getCourse($id);

        $this->assertInstanceOf(Course::class, $course);
        $this->assertEquals($name, $course->getName());
        $this->assertEquals($id, $course->getId());
        $this->assertTrue(is_array($course->getStudents()));
    }

    /**
     * @test
     */
    public function getAll_withNoArgument_ShouldReturnListOfCourses(): void
    {
        $courseExpected = new Course();
        $courseExpected->setId(1);
        $courseExpected->setName('Course 1');

        $this->courseRepository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn([ $courseExpected ]);

        $courses = $this->courseService->getAll();

        foreach ($courses as $course) {
            $this->assertInstanceOf(Course::class, $course);
        }
    }

    /**
     * @test
     */
    public function getStudentsPerCourse_WithCourseId_ShouldReturnListOfCourses() {
        $courseId = 1;
        $courseName = 'Course';

        $course = new Course();
        $course->setId($courseId);
        $course->setName($courseName);

        $this->personCourseRepository
            ->expects($this->any())
            ->method('getStudentsPerCourse')
            ->with($course->getId())
            ->willReturn(
                [ new Person() ]
            );

        $result = $this->courseService->getStudentsPerCourse($course);
        foreach ($result as $item) {
            $this->assertInstanceOf(Person::class, $item);
        }
    }
}