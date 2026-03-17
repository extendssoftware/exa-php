<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer;

use PHPUnit\Framework\TestCase;

class TransformerPipelineTest extends TestCase
{
    /**
     * Transform.
     *
     * Asserts that internal transformers are invoked sequentially, each receiving the prior transformed value and
     * returning a new one.
     *
     * @covers \ExtendsSoftware\ExaPHP\Transformer\TransformerPipeline::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Transformer\TransformerPipeline::transform()
     *
     * @return void
     */
    public function testTransform(): void
    {
        $transformer1 = $this->createMock(TransformerInterface::class);
        $transformer1
            ->expects($this->once())
            ->method('transform')
            ->with('1')
            ->willReturn('2');

        $transformer2 = $this->createMock(TransformerInterface::class);
        $transformer2
            ->expects($this->once())
            ->method('transform')
            ->with('2')
            ->willReturn('3');

        $transformer3 = $this->createMock(TransformerInterface::class);
        $transformer3
            ->expects($this->once())
            ->method('transform')
            ->with('3')
            ->willReturn('4');

        $pipeline = new TransformerPipeline([
            $transformer1,
            $transformer2,
            $transformer3,
        ]);
        $value = $pipeline->transform('1');

        $this->assertSame('4', $value);
    }

    /**
     * Transform with no transformers.
     *
     * Asserts that the value is returned when no transformers are given.
     *
     * @covers \ExtendsSoftware\ExaPHP\Transformer\TransformerPipeline::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Transformer\TransformerPipeline::transform()
     *
     * @return void
     */
    public function testTransformWithNoTransformers(): void
    {
        $pipeline = new TransformerPipeline([]);
        $value = $pipeline->transform('test');

        $this->assertSame('test', $value);
    }

    /**
     * Transform with non-string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Transformer\TransformerPipeline::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Transformer\TransformerPipeline::transform()
     *
     * @return void
     */
    public function testTransformWithNonString(): void
    {
        $transformer = $this->createMock(TransformerInterface::class);
        $transformer
            ->expects($this->once())
            ->method('transform')
            ->with(123)
            ->willReturn(456);

        $pipeline = new TransformerPipeline([$transformer]);
        $value = $pipeline->transform(123);

        $this->assertSame(456, $value);
    }
}