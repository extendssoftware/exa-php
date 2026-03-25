<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(NumericOnlyTransformer::class, 'process')]
class NumericOnlyTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAlphanumericCharacters(): void
    {
        $processer = new NumericOnlyTransformer();
        $result = $processer->process('a1-B+2@/ ');

        $this->assertSame('12', $result->getValue());
        $this->assertTrue($result->isValid());
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $processer = new NumericOnlyTransformer();
        $result = $processer->process(123);

        $this->assertFalse($result->isValid());
    }
}
