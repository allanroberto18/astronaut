<?php declare(strict_types=1);

namespace Tests\Provider;

use PHPUnit\Framework\TestCase;
use App\Provider\CommandProvider;
use PHPUnit\Framework\MockObject\MockObject;
use App\Contracts\Provider\ConnectionProviderInterface;

class CommandProviderTest extends TestCase
{
    /**
     * @var MockObject $connectionProvider
     */
    private $connectionProvider;

    /**
     * @var MockObject $pdo
     */
    private $pdo;

    /**
     * @var MockObject $pdoStatement
     */
    private $pdoStatement;

    /**
     * @var CommandProvider $commandProvider
     */
    private $commandProvider;

    protected function setUp(): void
    {
        $this->connectionProvider = $this
            ->getMockBuilder(ConnectionProviderInterface::class)
            ->getMock();

        $this->pdo = $this
            ->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->pdoStatement = $this
            ->getMockBuilder(\PDOStatement::class)
            ->getMock();

        $this->commandProvider = new CommandProvider($this->connectionProvider);

        parent::setUp();
    }

    /**
     * @test
     * @dataProvider selectAllProvider
     * @param string $sql
     * @param array $values
     */
    public function executeCommand_WithSQLCommandAndValuesToExecuteOnDB_ShouldReturnPdoStatement(string $sql, array $values): void
    {
        $this->mockDependencies($sql, $values);

        $pdoStatement = $this->commandProvider->executeCommand($sql, $values);

        $this->assertInstanceOf(\PDOStatement::class, $pdoStatement);
    }

    /**
     * @test
     * @dataProvider selectAllProvider
     * @param string $sql
     * @param array $values
     */
    public function getAll_WithSQLCommandAndValuesToExecuteOnDB_ShouldReturnArray(string $sql, array $values): void
    {
        $this->mockDependencies($sql, $values);

        $this->pdoStatement
            ->expects($this->any())
            ->method('fetchAll')
            ->willReturn(
                [
                    0 => [ 'id' =>  1, 'name' => 'Course 1' ],
                    1 => [ 'id' =>  2, 'name' => 'Course 2' ],
                    2 => [ 'id' =>  3, 'name' => 'Course 3' ],
                    3 => [ 'id' =>  4, 'name' => 'Course 4' ],
                    4 => [ 'id' =>  5, 'name' => 'Course 5' ]
                ]
            );

        $result = $this->commandProvider->getAll($sql, $values);

        $this->assertTrue(is_array($result));
    }

    /**
     * @test
     * @dataProvider selectByIdProvider
     * @param string $sql
     * @param array $values
     */
    public function getById_WithSQLCommandAndValuesToExecuteOnDB_ShouldReturnArray(string $sql, array $values): void
    {
        $this->mockDependencies($sql, $values);

        $this->pdoStatement
            ->expects($this->any())
            ->method('fetch')
            ->willReturn([ 'id' =>  1, 'name' => 'Course 1' ]);

        $result = $this->commandProvider->getById($sql, $values);

        $this->assertTrue(is_array($result));
    }

    /**
     * @test
     * @dataProvider insertProvider
     * @param string $sql
     * @param array $values
     */
    public function getId_WithSQLCommandAndValuesToExecuteOnDB_ShouldReturnInt(string $sql, array $values): void
    {
        $this->mockDependencies($sql, $values);

        $this->pdo
            ->expects($this->any())
            ->method('lastInsertId')
            ->willReturn(1);

        $id = $this->commandProvider->getId($sql, $values);

        $this->assertTrue(is_int($id));
    }

    private function mockDependencies(string $sql, array $values): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('beginTransaction')
            ->willReturn(true);

        $this->pdo
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo($sql))
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->with($values)
            ->willReturn(true);

        $this->connectionProvider
            ->expects($this->once())
            ->method('getConnection')
            ->willReturn($this->pdo);
    }

    public function selectAllProvider(): array
    {
        return [
            [
                'SELECT * FROM person ORDER BY id ASC', [ ]
            ],
            [
                'SELECT * FROM course ORDER BY id ASC', [ ]
            ],
            [
                'SELECT * FROM person_course WHERE person_id = ? AND course_id = ?', [ 1, 1]
            ],
        ];
    }

    public function selectByIdProvider(): array
    {
        return [
            [
                'SELECT * FROM person WHERE id = ?', [ 1 ]
            ],
            [
                'SELECT * FROM course WHERE id = ?', [ 1 ]
            ],
            [
                'SELECT * FROM person_course WHERE person_id = ? AND course_id = ?', [ 1, 1]
            ],
        ];
    }

    public function insertProvider(): array
    {
        return [
            [
                'INSERT INTO person (name) VALUES (?)', [ 'Person Test']
            ],
            [
                'INSERT INTO course (name) VALUES (?)', [ 'Course Test']
            ],
            [
                'INSERT INTO person_course (person_id, course_id) VALUES (?, ?)', [ 1, 1]
            ],
        ];
    }

}