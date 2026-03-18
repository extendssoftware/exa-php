<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(TrimWhitespaceTransformer::class, 'transform')]
class TrimWhitespaceTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAsciiAlphanumericCharacters(): void
    {
        $transformer = new TrimWhitespaceTransformer();
        $value = $transformer->transform(' This  Is  Some  Dummy  Text.  ');

        $this->assertSame('This  Is  Some  Dummy  Text.', $value);
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $transformer = new TrimWhitespaceTransformer();
        $value = $transformer->transform(123);

        $this->assertSame(123, $value);
    }
}
