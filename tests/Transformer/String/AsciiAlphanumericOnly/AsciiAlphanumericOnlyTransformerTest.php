<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String\AsciiAlphanumericOnly;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(AsciiAlphanumericOnlyTransformer::class, 'transform')]
class AsciiAlphanumericOnlyTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAsciiAlphanumericCharacters(): void
    {
        $transformer = new AsciiAlphanumericOnlyTransformer();
        $value = $transformer->transform(' @ÁbC-12 3_Ⅱ'); // Container Unicode letter and number.

        $this->assertSame('bC123', $value);
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $transformer = new AsciiAlphanumericOnlyTransformer();
        $value = $transformer->transform(123);

        $this->assertSame(123, $value);
    }
}