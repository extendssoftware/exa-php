<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Transformer\String;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(UppercaseTransformer::class, 'process')]
class UppercaseTransformerTest extends TestCase
{
    #[Test]
    public function removesNonAlphanumericCharacters(): void
    {
        $processer = new UppercaseTransformer();
        $result = $processer->process('This Is Some Dummy Text.');

        $this->assertSame('THIS IS SOME DUMMY TEXT.', $result->getValue());
        $this->assertTrue($result->isValid());
    }

    #[Test]
    public function returnsValueWhenInputIsNotString(): void
    {
        $processer = new UppercaseTransformer();
        $result = $processer->process(123);

        $this->assertFalse($result->isValid());
    }
}
