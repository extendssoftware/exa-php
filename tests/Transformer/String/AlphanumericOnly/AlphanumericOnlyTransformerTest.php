<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String\AlphanumericOnly;

use PHPUnit\Framework\TestCase;

class AlphanumericOnlyTransformerTest extends TestCase
{
    /**
     * Asserts that the transformer will only return alphanumeric characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Transformer\String\AlphanumericOnly\AlphanumericOnlyTransformer::transform()
     *
     * @return void
     */
    public function testTransform(): void
    {
        $transformer = new AlphanumericOnlyTransformer();
        $value = $transformer->transform(' @ÁbC-12 3_');

        $this->assertSame('ÁbC123', $value);
    }

    /**
     * Asserts that the transformer will return the value when it is not a string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Transformer\String\AlphanumericOnly\AlphanumericOnlyTransformer::transform()
     *
     * @return void
     */
    public function testTransformWithNonString(): void
    {
        $transformer = new AlphanumericOnlyTransformer();
        $value = $transformer->transform(123);

        $this->assertSame(123, $value);
    }
}
