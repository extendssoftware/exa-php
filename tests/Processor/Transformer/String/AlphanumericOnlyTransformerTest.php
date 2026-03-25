<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(AlphanumericOnlyTransformer::class, 'process')]
class AlphanumericOnlyTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAlphanumericCharacters(): void
    {
        $processer = new AlphanumericOnlyTransformer();
        $result = $processer->process('a1-B+2@/ ');

        $this->assertSame('a1B2', $result->getValue());
        $this->assertTrue($result->isValid());
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $processer = new AlphanumericOnlyTransformer();
        $result = $processer->process(123);

        $this->assertFalse($result->isValid());
    }
}
