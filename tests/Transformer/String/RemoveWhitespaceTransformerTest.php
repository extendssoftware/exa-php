<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RemoveWhitespaceTransformer::class, 'transform')]
class RemoveWhitespaceTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAsciiAlphanumericCharacters(): void
    {
        $transformer = new RemoveWhitespaceTransformer();
        $value = $transformer->transform('This is some text.');

        $this->assertSame('Thisissometext.', $value);
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $transformer = new RemoveWhitespaceTransformer();
        $value = $transformer->transform(123);

        $this->assertSame(123, $value);
    }
}
