<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication;

use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use PHPUnit\Framework\TestCase;

class AuthenticationInfoTest extends TestCase
{
    /**
     * Get identity.
     *
     * Test that correct identity will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authentication\AuthenticationInfo::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Authentication\AuthenticationInfo::getIdentity()
     */
    public function testGetIdentifier(): void
    {
        $identity = $this->createMock(IdentityInterface::class);

        /**
         * @var IdentityInterface $identity
         */
        $info = new AuthenticationInfo($identity);

        $this->assertSame($identity, $info->getIdentity());
    }
}
