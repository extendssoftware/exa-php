<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RemoveWhitespaceTransformer::class, 'process')]
class RemoveWhitespaceTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAsciiAlphanumericCharacters(): void
    {
        $processer = new RemoveWhitespaceTransformer();
        $result = $processer->process('This is some text.');

        $this->assertSame('Thisissometext.', $result->getValue());
        $this->assertTrue($result->isValid());
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $processer = new RemoveWhitespaceTransformer();
        $result = $processer->process(123);


        $this->assertFalse($result->isValid());
    }
}
