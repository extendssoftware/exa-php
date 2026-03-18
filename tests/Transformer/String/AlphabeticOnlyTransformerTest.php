<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(AlphabeticOnlyTransformer::class, 'transform')]
class AlphabeticOnlyTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAsciiAlphanumericCharacters(): void
    {
        $transformer = new AlphabeticOnlyTransformer();
        $value = $transformer->transform('a1-B+2@/ ');

        $this->assertSame('aB', $value);
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $transformer = new AlphabeticOnlyTransformer();
        $value = $transformer->transform(123);

        $this->assertSame(123, $value);
    }
}
