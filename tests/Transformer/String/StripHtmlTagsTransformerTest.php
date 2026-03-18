<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(StripHtmlTagsTransformer::class, 'transform')]
class StripHtmlTagsTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAsciiAlphanumericCharacters(): void
    {
        $transformer = new StripHtmlTagsTransformer();
        $value = $transformer->transform('This is <strong>some</strong> text.');

        $this->assertSame('This is some text.', $value);
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $transformer = new StripHtmlTagsTransformer();
        $value = $transformer->transform(123);

        $this->assertSame(123, $value);
    }
}
