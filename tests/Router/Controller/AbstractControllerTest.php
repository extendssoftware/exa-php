<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Controller;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\Response;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Controller\Exception\ActionNotFound;
use ExtendsSoftware\ExaPHP\Router\Controller\Exception\ParameterNotFound;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class AbstractControllerTest extends TestCase
{
    /**
     * Dummy abstract controller.
     *
     * @var AbstractController
     */
    private $controller;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->controller = new class extends AbstractController {
            /**
             * @param int       $someId
             * @param bool|null $allowsNull
             * @param string    $defaultValue
             *
             * @return ResponseInterface
             */
            public function fooFancyActionAction(
                int $someId,
                ?bool $allowsNull,
                string $defaultValue = 'string'
            ): ResponseInterface {
                return (new Response())->withBody([
                    'request' => $this->getRequest(),
                    'routeMatch' => $this->getRouteMatch(),
                    'someId' => $someId,
                    'allowsNull' => $allowsNull,
                    'defaultValue' => $defaultValue,
                ]);
            }
        };
    }


    /**
     * Execute.
     *
     * Test that $request can be executed to $controller and $response will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Controller\AbstractController::execute()
     * @covers \ExtendsSoftware\ExaPHP\Router\Controller\AbstractController::getRequest()
     * @covers \ExtendsSoftware\ExaPHP\Router\Controller\AbstractController::getRouteMatch()
     */
    public function testExecute(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([
                'action' => 'foo.fancy-action',
                'someId' => 33,
            ]);

        $match
            ->expects($this->once())
            ->method('getParameter')
            ->with('action')
            ->willReturn('foo.fancy-action');

        /**
         * @var RequestInterface    $request
         * @var RouteMatchInterface $match
         */
        $response = $this->controller->execute($request, $match);

        $this->assertIsObject($response);
        $this->assertSame([
            'request' => $request,
            'routeMatch' => $match,
            'someId' => 33,
            'allowsNull' => null,
            'defaultValue' => 'string',
        ], $response->getBody());
    }

    /**
     * Action not found.
     *
     * Test that action attribute can not be found in $request and an exception will be thrown.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Controller\AbstractController::execute()
     * @covers \ExtendsSoftware\ExaPHP\Router\Controller\Exception\ActionNotFound::__construct()
     */
    public function testActionNotFound(): void
    {
        $this->expectException(ActionNotFound::class);
        $this->expectExceptionMessage('No controller action was found in request.');

        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->method('getParameters')
            ->willReturn([]);

        /**
         * @var RequestInterface    $request
         * @var RouteMatchInterface $match
         */
        $this->controller->execute($request, $match);
    }

    /**
     * Parameter not found.
     *
     * Test that parameter value can not be determined and an exception will be thrown.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Controller\AbstractController::execute()
     * @covers \ExtendsSoftware\ExaPHP\Router\Controller\Exception\ParameterNotFound::__construct()
     */
    public function testParameterNotFound(): void
    {
        $this->expectException(ParameterNotFound::class);
        $this->expectExceptionMessage(
            'Parameter name "someId" can not be found in route match ' .
            'parameters and has no default value or allows null.'
        );

        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getParameter')
            ->with('action')
            ->willReturn('fooFancyAction');

        /**
         * @var RequestInterface    $request
         * @var RouteMatchInterface $match
         */
        $this->controller->execute($request, $match);
    }
}
