<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Framework\Http\Middleware\Logger;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Logger\Exception\ReferencedException;
use ExtendsSoftware\ExaPHP\Logger\Exception\ReferencedExceptionInterface;
use ExtendsSoftware\ExaPHP\Logger\LoggerInterface;
use ExtendsSoftware\ExaPHP\Logger\Priority\Error\ErrorPriority;
use Throwable;

class LoggerMiddleware implements MiddlewareInterface
{
    /**
     * LoggerMiddleware constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /**
     * @inheritDoc
     * @throws ReferencedExceptionInterface
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        try {
            return $chain->proceed($request);
        } catch (Throwable $exception) {
            $reference = uniqid();

            $this->logger->log(
                $exception->getMessage(),
                new ErrorPriority(),
                [
                    'reference' => $reference,
                ]
            );

            throw new ReferencedException($exception, $reference);
        }
    }
}
