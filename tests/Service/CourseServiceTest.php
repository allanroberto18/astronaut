<?php declare(strict_types=1);


namespace Tests\Service;


use App\Contracts\Repository\CourseRepositoryInterface;
use App\Model\Course;
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
     * @var CourseService $courseService
     */
    private $courseService;

    protected function setUp(): void
    {
        $this->courseRepository = $this
            ->getMockBuilder(CourseRepositoryInterface::class)
            ->getMock();

        $this->courseService = new CourseService(
            $this->courseRepository
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

        $course = $this->courseService->getCourse($id);

        $this->assertInstanceOf(Course::class, $course);
        $this->assertEquals($name, $course->getName());
        $this->assertEquals($id, $course->getId());
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
}