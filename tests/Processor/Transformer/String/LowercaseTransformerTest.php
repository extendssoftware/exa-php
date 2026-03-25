<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(LowercaseTransformer::class, 'process')]
class LowercaseTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAlphanumericCharacters(): void
    {
        $processer = new LowercaseTransformer();
        $result = $processer->process('This Is Some Dummy Text.');

        $this->assertSame('this is some dummy text.', $result->getValue());
        $this->assertTrue($result->isValid());
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $processer = new LowercaseTransformer();
        $result = $processer->process(123);

        $this->assertFalse($result->isValid());
    }
}
