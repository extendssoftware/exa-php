<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(AsciiLettersOnlyTransformer::class, 'transform')]
class AsciiLettersOnlyTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAsciiAlphanumericCharacters(): void
    {
        $transformer = new AsciiLettersOnlyTransformer();
        $value = $transformer->transform("  A1Áβ٣\t\n\r\x00\x1F\x7F !@#-_ 　𐍈९");

        $this->assertSame('A', $value);
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $transformer = new AsciiLettersOnlyTransformer();
        $value = $transformer->transform(123);

        $this->assertSame(123, $value);
    }
}
