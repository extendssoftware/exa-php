<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(UppercaseTransformer::class, 'transform')]
class UppercaseTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAlphanumericCharacters(): void
    {
        $transformer = new UppercaseTransformer();
        $value = $transformer->transform('aBc');

        $this->assertSame('ABC', $value);
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $transformer = new UppercaseTransformer();
        $value = $transformer->transform(123);

        $this->assertSame(123, $value);
    }
}
