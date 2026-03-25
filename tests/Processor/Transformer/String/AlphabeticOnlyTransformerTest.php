<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(AlphabeticOnlyTransformer::class, 'process')]
class AlphabeticOnlyTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAsciiAlphanumericCharacters(): void
    {
        $processer = new AlphabeticOnlyTransformer();
        $result = $processer->process('a1-B+2@/ ');

        $this->assertSame('aB', $result->getValue());
        $this->assertTrue($result->isValid());
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $processer = new AlphabeticOnlyTransformer();
        $result = $processer->process(123);

        $this->assertFalse($result->isValid());
    }
}
