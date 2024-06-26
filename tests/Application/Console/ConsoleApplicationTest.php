<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Console;

use Exception;
use ExtendsSoftware\ExaPHP\Application\Console\Exception\TaskExecuteFailed;
use ExtendsSoftware\ExaPHP\Application\Console\Exception\TaskNotFound;
use ExtendsSoftware\ExaPHP\Application\Console\Exception\TaskParameterMissing;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Shell\Command\CommandInterface;
use ExtendsSoftware\ExaPHP\Shell\ShellInterface;
use ExtendsSoftware\ExaPHP\Shell\ShellResultInterface;
use ExtendsSoftware\ExaPHP\Shell\Task\TaskException;
use ExtendsSoftware\ExaPHP\Shell\Task\TaskInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ConsoleApplicationTest extends TestCase
{
    /**
     * Run.
     *
     * Test that task will be executed for given command.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Console\ConsoleApplication::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Application\Console\ConsoleApplication::run()
     */
    public function testRun(): void
    {
        $GLOBALS['argv'] = [
            'test.php',
            'do.task',
        ];

        $command = $this->createMock(CommandInterface::class);
        $command
            ->expects($this->once())
            ->method('getParameter')
            ->with('task')
            ->willReturn(stdClass::class);

        $result = $this->createMock(ShellResultInterface::class);
        $result
            ->expects($this->once())
            ->method('getCommand')
            ->willReturn($command);

        $result
            ->expects($this->exactly(2))
            ->method('getData')
            ->willReturn([
                'foo' => 'bar',
            ]);

        $shell = $this->createMock(ShellInterface::class);
        $shell
            ->expects($this->once())
            ->method('process')
            ->with([
                'do.task',
            ])
            ->willReturn($result);

        $task = $this->createMock(TaskInterface::class);
        $task
            ->expects($this->once())
            ->method('execute')
            ->with([
                'foo' => 'bar',
            ]);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(stdClass::class, [
                'foo' => 'bar',
            ])
            ->willReturn($task);

        /**
         * @var ShellInterface          $shell
         * @var ServiceLocatorInterface $serviceLocator
         */
        $terminal = new ConsoleApplication($shell, $serviceLocator, []);
        $terminal->bootstrap();
    }

    /**
     * Task parameter missing.
     *
     * Test that an exception will be thrown when task parameter is missing in command.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Console\ConsoleApplication::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Application\Console\ConsoleApplication::run()
     * @covers \ExtendsSoftware\ExaPHP\Application\Console\Exception\TaskParameterMissing::__construct()
     */
    public function testTaskParameterMissing(): void
    {
        $this->expectException(TaskParameterMissing::class);
        $this->expectExceptionMessage('Task parameter not defined for command "do.task".');

        $GLOBALS['argv'] = [
            'test.php',
            'do.task',
        ];

        $command = $this->createMock(CommandInterface::class);
        $command
            ->expects($this->once())
            ->method('getParameter')
            ->with('task')
            ->willReturn(null);

        $command
            ->expects($this->once())
            ->method('getName')
            ->willReturn('do.task');

        $result = $this->createMock(ShellResultInterface::class);
        $result
            ->expects($this->once())
            ->method('getCommand')
            ->willReturn($command);

        $shell = $this->createMock(ShellInterface::class);
        $shell
            ->expects($this->once())
            ->method('process')
            ->with([
                'do.task',
            ])
            ->willReturn($result);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ShellInterface          $shell
         * @var ServiceLocatorInterface $serviceLocator
         */
        $terminal = new ConsoleApplication($shell, $serviceLocator, []);
        $terminal->bootstrap();
    }

    /**
     * Task not found.
     *
     * Test that an exception will be thrown when task can not be found by service locator.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Console\ConsoleApplication::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Application\Console\ConsoleApplication::run()
     * @covers \ExtendsSoftware\ExaPHP\Application\Console\Exception\TaskNotFound::__construct()
     */
    public function testTaskNotFound(): void
    {
        $this->expectException(TaskNotFound::class);
        $this->expectExceptionMessage(
            'Task for command "do.task" can not be found, see previous exception for more details.'
        );

        $GLOBALS['argv'] = [
            'test.php',
            'do.task',
        ];

        $command = $this->createMock(CommandInterface::class);
        $command
            ->expects($this->once())
            ->method('getParameter')
            ->with('task')
            ->willReturn(stdClass::class);

        $command
            ->expects($this->once())
            ->method('getName')
            ->willReturn('do.task');

        $result = $this->createMock(ShellResultInterface::class);
        $result
            ->expects($this->once())
            ->method('getCommand')
            ->willReturn($command);

        $shell = $this->createMock(ShellInterface::class);
        $shell
            ->expects($this->once())
            ->method('process')
            ->with([
                'do.task',
            ])
            ->willReturn($result);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(stdClass::class)
            ->willThrowException(new class extends Exception implements ServiceLocatorException {
            });

        /**
         * @var ShellInterface          $shell
         * @var ServiceLocatorInterface $serviceLocator
         */
        $terminal = new ConsoleApplication($shell, $serviceLocator, []);
        $terminal->bootstrap();
    }

    /**
     * Task parameter missing.
     *
     * Test that an exception will be thrown when task execution fails.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Console\ConsoleApplication::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Application\Console\ConsoleApplication::run()
     * @covers \ExtendsSoftware\ExaPHP\Application\Console\Exception\TaskExecuteFailed::__construct()
     */
    public function testTaskExecuteFailed(): void
    {
        $this->expectException(TaskExecuteFailed::class);
        $this->expectExceptionMessage(
            'Failed to execute task for command "do.task", see previous exception for more details.'
        );

        $GLOBALS['argv'] = [
            'test.php',
            'do.task',
        ];

        $command = $this->createMock(CommandInterface::class);
        $command
            ->expects($this->once())
            ->method('getParameter')
            ->with('task')
            ->willReturn(stdClass::class);

        $command
            ->method('getName')
            ->willReturn('do.task');

        $result = $this->createMock(ShellResultInterface::class);
        $result
            ->expects($this->once())
            ->method('getCommand')
            ->willReturn($command);

        $result
            ->expects($this->exactly(2))
            ->method('getData')
            ->willReturn([
                'foo' => 'bar',
            ]);

        $shell = $this->createMock(ShellInterface::class);
        $shell
            ->expects($this->once())
            ->method('process')
            ->with([
                'do.task',
            ])
            ->willReturn($result);

        $task = $this->createMock(TaskInterface::class);
        $task
            ->expects($this->once())
            ->method('execute')
            ->with([
                'foo' => 'bar',
            ])
            ->willThrowException(new class extends Exception implements TaskException {
            });

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(stdClass::class, [
                'foo' => 'bar',
            ])
            ->willReturn($task);

        /**
         * @var ShellInterface          $shell
         * @var ServiceLocatorInterface $serviceLocator
         */
        $terminal = new ConsoleApplication($shell, $serviceLocator, []);
        $terminal->bootstrap();
    }
}
