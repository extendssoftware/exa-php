<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Writer\Pdo;

use ExtendsSoftware\ExaPHP\Logger\Decorator\DecoratorInterface;
use ExtendsSoftware\ExaPHP\Logger\Filter\FilterInterface;
use ExtendsSoftware\ExaPHP\Logger\LogInterface;
use ExtendsSoftware\ExaPHP\Logger\Writer\AbstractWriter;
use ExtendsSoftware\ExaPHP\Logger\Writer\Pdo\Exception\StatementFailedWithError;
use ExtendsSoftware\ExaPHP\Logger\Writer\Pdo\Exception\StatementFailedWithException;
use ExtendsSoftware\ExaPHP\Logger\Writer\WriterInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PDO;
use PDOException;

class PdoWriter extends AbstractWriter
{
    /**
     * PdoWriter constructor.
     *
     * @param PDO    $pdo
     * @param string $table
     */
    public function __construct(private readonly PDO $pdo, private readonly string $table = 'log')
    {
    }

    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): WriterInterface {
        $pdo = $serviceLocator->getService(PDO::class);

        /**
         * @var PDO $pdo
         */
        $writer = new PdoWriter(
            $pdo,
            $extra['table'] ?? null
        );

        foreach ($extra['filters'] ?? [] as $config) {
            $filter = $serviceLocator->getService($config['name'], $config['options'] ?? []);

            if ($filter instanceof FilterInterface) {
                $writer->addFilter($filter);
            }
        }

        foreach ($extra['decorators'] ?? [] as $config) {
            $decorator = $serviceLocator->getService($config['name'], $config['options'] ?? []);
            if ($decorator instanceof DecoratorInterface) {
                $writer->addDecorator($decorator);
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
            $log = $this->decorate($log);

            /** @noinspection SqlResolve */
            /** @noinspection SqlNoDataSourceInspection */
            $statement = $this->pdo->prepare(sprintf(
                'INSERT INTO `%s` (`value`, `keyword`, `date_time`, `message`, `meta_data`) ' .
                'VALUES (:value, :keyword, :date_time, :message, :meta_data)',
                $this->table
            ));

            $metaData = $log->getMetaData() ?: null;
            if (is_array($metaData)) {
                $metaData = json_encode($metaData, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_SLASHES);
            }

            $priority = $log->getPriority();
            $parameters = [
                'value' => $priority->getValue(),
                'keyword' => strtoupper($priority->getKeyword()),
                'date_time' => $log
                    ->getDateTime()
                    ->format('Y-m-d H:i:s'),
                'message' => $log->getMessage(),
                'meta_data' => $metaData,
            ];

            try {
                $result = $statement->execute($parameters);
            } catch (PDOException $exception) {
                throw new StatementFailedWithException($exception, $log->getMessage());
            }

            if (!$result) {
                throw new StatementFailedWithError($statement->errorCode(), $log->getMessage());
            }
        }

        return $this;
    }
}
