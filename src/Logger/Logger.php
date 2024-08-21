<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger;

use ExtendsSoftware\ExaPHP\Logger\Decorator\DecoratorInterface;
use ExtendsSoftware\ExaPHP\Logger\Priority\Alert\AlertPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Critical\CriticalPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Debug\DebugPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Emergency\EmergencyPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Error\ErrorPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Informational\InformationalPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Notice\NoticePriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\Logger\Priority\Warning\WarningPriority;
use ExtendsSoftware\ExaPHP\Logger\Writer\WriterException;
use ExtendsSoftware\ExaPHP\Logger\Writer\WriterInterface;
use Throwable;

class Logger implements LoggerInterface
{
    /**
     * Writer queue.
     *
     * @var LoggerWriter[]
     */
    private array $writers = [];

    /**
     * Decorators.
     *
     * @var DecoratorInterface[]
     */
    private array $decorators = [];

    /**
     * @inheritDoc
     * @throws WriterException When writer failed to write.
     */
    public function log(
        string $message,
        PriorityInterface $priority = null,
        array $metaData = null,
        Throwable $throwable = null,
    ): LoggerInterface {
        $log = new Log($message, $priority, null, $metaData, $throwable);
        $log = $this->decorate($log);

        foreach ($this->writers as $writer) {
            $writer
                ->getWriter()
                ->write($log);

            if ($writer->mustInterrupt()) {
                break;
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     * @throws WriterException
     */
    public function emerg(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface
    {
        return $this->log($message, new EmergencyPriority(), $metaData, $throwable);
    }

    /**
     * @inheritDoc
     * @throws WriterException
     */
    public function alert(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface
    {
        return $this->log($message, new AlertPriority(), $metaData, $throwable);
    }

    /**
     * @inheritDoc
     * @throws WriterException
     */
    public function crit(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface
    {
        return $this->log($message, new CriticalPriority(), $metaData, $throwable);
    }

    /**
     * @inheritDoc
     * @throws WriterException
     */
    public function error(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface
    {
        return $this->log($message, new ErrorPriority(), $metaData, $throwable);
    }

    /**
     * @inheritDoc
     * @throws WriterException
     */
    public function warning(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface
    {
        return $this->log($message, new WarningPriority(), $metaData, $throwable);
    }

    /**
     * @inheritDoc
     * @throws WriterException
     */
    public function notice(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface
    {
        return $this->log($message, new NoticePriority(), $metaData, $throwable);
    }

    /**
     * @inheritDoc
     * @throws WriterException
     */
    public function info(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface
    {
        return $this->log($message, new InformationalPriority(), $metaData, $throwable);
    }

    /**
     * @inheritDoc
     * @throws WriterException
     */
    public function debug(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface
    {
        return $this->log($message, new DebugPriority(), $metaData, $throwable);
    }

    /**
     * Add writer to logger.
     *
     * When interrupt is true and the writer's write method will not throw an exception, the next writer won't be
     * called.
     *
     * @param WriterInterface $writer
     * @param bool|null       $interrupt
     *
     * @return Logger
     */
    public function addWriter(WriterInterface $writer, bool $interrupt = null): Logger
    {
        $this->writers[] = new LoggerWriter($writer, $interrupt ?: false);

        return $this;
    }

    /**
     * Add decorator.
     *
     * @param DecoratorInterface $decorator
     *
     * @return Logger
     */
    public function addDecorator(DecoratorInterface $decorator): Logger
    {
        $this->decorators[] = $decorator;

        return $this;
    }

    /**
     * Decorate log and return new instance.
     *
     * @param LogInterface $log
     *
     * @return LogInterface
     */
    protected function decorate(LogInterface $log): LogInterface
    {
        foreach ($this->decorators as $decorator) {
            $log = $decorator->decorate($log);
        }

        return $log;
    }
}
