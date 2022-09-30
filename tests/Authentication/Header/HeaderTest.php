<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Header;

use PHPUnit\Framework\TestCase;

class HeaderTest extends TestCase
{
    /**
     * Test that getters will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Header\Header::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Header\Header::getScheme()
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Header\Header::getCredentials()
     */
    public function testGetters(): void
    {
        $header = new Header('Bearer', 'ed6ed1ec-769b-4f35-b74a-d4d4205f1d88');

        $this->assertSame('Bearer', $header->getScheme());
        $this->assertSame('ed6ed1ec-769b-4f35-b74a-d4d4205f1d88', $header->getCredentials());
    }
}
