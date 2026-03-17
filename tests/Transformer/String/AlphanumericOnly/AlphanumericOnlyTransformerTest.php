<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String\AlphanumericOnly;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(AlphanumericOnlyTransformer::class, 'transform')]
class AlphanumericOnlyTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAlphanumericCharacters(): void
    {
        $transformer = new AlphanumericOnlyTransformer();
        $value = $transformer->transform(' @ÁbC-12 3_');

        $this->assertSame('ÁbC123', $value);
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $transformer = new AlphanumericOnlyTransformer();
        $value = $transformer->transform(123);

        $this->assertSame(123, $value);
    }
}
