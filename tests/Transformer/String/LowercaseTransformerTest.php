<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(LowercaseTransformer::class, 'transform')]
class LowercaseTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAlphanumericCharacters(): void
    {
        $transformer = new LowercaseTransformer();
        $value = $transformer->transform('aBc');

        $this->assertSame('abc', $value);
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $transformer = new LowercaseTransformer();
        $value = $transformer->transform(123);

        $this->assertSame(123, $value);
    }
}
