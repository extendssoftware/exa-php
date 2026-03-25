<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor;

use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class AbstractProcessorTest extends TestCase
{
    /**
     * Dummy abstract processor.
     *
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->processor = new class extends AbstractProcessor {
            /**
             * @inheritDoc
             */
            protected function getTemplates(): array
            {
                return [
                    'bar' => 'Fancy error message.',
                ];
            }

            /**
             * @inheritDoc
             */
            public function process($value, $context = null): ResultInterface
            {
                if ($context === true) {
                    return $this->getValidResult($value);
                }
                if ($context === false) {
                    return $this->getInvalidResult('bar', []);
                }

                return $this->getInvalidResult('foo', []);
            }
        };
    }

    /**
     * Valid result.
     *
     * Test that a valid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\AbstractProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\AbstractProcessor::getValidResult()
     */
    public function testValidResult(): void
    {
        $result = $this->processor->process('foo', true);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
    }

    /**
     * Invalid result.
     *
     * Test that an invalid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\AbstractProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\AbstractProcessor::getInvalidResult()
     */
    public function testInvalidResult(): void
    {
        $result = $this->processor->process('foo', false);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Template not found.
     *
     * Test that exception TemplateNotFound will be thrown when a template for key 'foo' cannot be found.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\AbstractProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\AbstractProcessor::getInvalidResult()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound::__construct()
     */
    public function testTemplateNotFound(): void
    {
        $this->expectException(TemplateNotFound::class);
        $this->expectExceptionMessage('No invalid result template found for key "foo".');

        $this->processor->process('foo');
    }
}
