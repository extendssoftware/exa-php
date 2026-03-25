<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(TrimWhitespaceTransformer::class, 'process')]
class TrimWhitespaceTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAsciiAlphanumericCharacters(): void
    {
        $processer = new TrimWhitespaceTransformer();
        $result = $processer->process(' This  Is  Some  Dummy  Text.  ');

        $this->assertSame('This  Is  Some  Dummy  Text.', $result->getValue());
        $this->assertTrue($result->isValid());
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $processer = new TrimWhitespaceTransformer();
        $result = $processer->process(123);

        $this->assertFalse($result->isValid());
    }
}
