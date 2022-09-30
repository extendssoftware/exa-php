<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Result\Valid;

use PHPUnit\Framework\TestCase;

class ValidResultTest extends TestCase
{
    /**
     * Methods.
     *
     * Test that methods return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult::isValid()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult::jsonSerialize()
     */
    public function testMethods(): void
    {
        $result = new ValidResult();

        $this->assertTrue($result->isValid());
        $this->assertNull($result->jsonSerialize());
    }
}
