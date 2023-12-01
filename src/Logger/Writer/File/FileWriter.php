<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Writer\File;

use ExtendsSoftware\ExaPHP\Logger\Filter\FilterInterface;
use ExtendsSoftware\ExaPHP\Logger\LogInterface;
use ExtendsSoftware\ExaPHP\Logger\Writer\AbstractWriter;
use ExtendsSoftware\ExaPHP\Logger\Writer\File\Exception\FileWriterFailed;
use ExtendsSoftware\ExaPHP\Logger\Writer\WriterInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class FileWriter extends AbstractWriter
{
    /**
     * FileWriter constructor.
     *
     * @param string $location
     * @param string $fileFormat
     * @param string $logFormat
     * @param string $newLine
     */
    public function __construct(
        private readonly string $location,
        private readonly string $fileFormat = 'Y-m-d',
        private readonly string $logFormat = '{datetime} {keyword} ({value}): {message} {metaData}',
        private readonly string $newLine = PHP_EOL
    ) {
    }

    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public static function factory(
        string $key,
        ServiceLocatorInterface $serviceLocator,
        array $extra = null
    ): WriterInterface {
        $writer = new FileWriter(
        /** @phpstan-ignore-next-line */
            $extra['location'],
            $extra['file_format'] ?? 'Y-m-d',
            $extra['log_format'] ?? '{datetime} {keyword} ({value}): {message} {metaData}',
            $extra['new_line'] ?? PHP_EOL
        );

        foreach ($extra['filters'] ?? [] as $config) {
            $filter = $serviceLocator->getService($config['name'], $config['options'] ?? []);

            if ($filter instanceof FilterInterface) {
                $writer->addFilter($filter);
            }
        }

        return $writer;
    }

    /**
     * @inheritDoc
     */
    public function write(LogInterface $log): WriterInterface
    {
        if (!$this->filter($log)) {
            $metaData = $log->getMetaData() ?: null;
            if (is_array($metaData)) {
                $metaData = json_encode($metaData, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_SLASHES);
            }

            $priority = $log->getPriority();
            $replacePairs = [
                '{datetime}' => $log
                    ->getDateTime()
                    ->format(DATE_ATOM),
                '{keyword}' => strtoupper($priority->getKeyword()),
                '{value}' => $priority->getValue(),
                '{message}' => $log->getMessage(),
                '{metaData}' => $metaData,
            ];

            $message = trim(strtr($this->logFormat, $replacePairs));
            $filename = sprintf(
                '%s/%s.log',
                rtrim($this->location, '/'),
                date($this->fileFormat)
            );

            $handle = @fopen($filename, 'ab');
            if (!is_resource($handle) || @fwrite($handle, $message . $this->newLine) === false) {
                throw new FileWriterFailed($message, $filename);
            }

            fclose($handle);
        }

        return $this;
    }
}
