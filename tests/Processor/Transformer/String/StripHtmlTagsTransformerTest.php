<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(StripHtmlTagsTransformer::class, 'process')]
class StripHtmlTagsTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAsciiAlphanumericCharacters(): void
    {
        $processer = new StripHtmlTagsTransformer();
        $result = $processer->process('This is <strong>some</strong> text.');

        $this->assertSame('This is some text.', $result->getValue());
        $this->assertTrue($result->isValid());
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $processer = new StripHtmlTagsTransformer();
        $result = $processer->process(123);

        $this->assertFalse($result->isValid());
    }
}
