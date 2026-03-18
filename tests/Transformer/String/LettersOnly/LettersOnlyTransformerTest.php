<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String\LettersOnly;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(LettersOnlyTransformer::class, 'transform')]
class LettersOnlyTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAlphanumericCharacters(): void
    {
        $transformer = new LettersOnlyTransformer();
        $value = $transformer->transform("  A1Áβ٣\t\n\r\x00\x1F\x7F !@#-_ 　𐍈९");

        $this->assertSame('AÁβ𐍈', $value);
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $transformer = new LettersOnlyTransformer();
        $value = $transformer->transform(123);

        $this->assertSame(123, $value);
    }
}
