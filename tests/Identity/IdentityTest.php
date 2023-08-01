<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity;

use PHPUnit\Framework\TestCase;

class IdentityTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that correct values will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Identity\Identity::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Identity\Identity::getIdentifier()
     * @covers \ExtendsSoftware\ExaPHP\Identity\Identity::isAuthenticated()
     * @covers \ExtendsSoftware\ExaPHP\Identity\Identity::getAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Identity\Identity::getAttribute()
     */
    public function testGetIdentifier(): void
    {
        $identity = new Identity('foo', true, [
            'foo' => 'bar',
        ]);

        $this->assertSame('foo', $identity->getIdentifier());
        $this->assertSame(true, $identity->isAuthenticated());
        $this->assertSame(['foo' => 'bar'], $identity->getAttributes());
        $this->assertSame('bar', $identity->getAttribute('foo'));
        $this->assertSame('foo', $identity->getAttribute('bar', 'foo'));
    }
}
