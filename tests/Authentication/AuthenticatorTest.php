<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication;

use ExtendsSoftware\ExaPHP\Authentication\Header\HeaderInterface;
use ExtendsSoftware\ExaPHP\Authentication\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AuthenticatorTest extends TestCase
{
    /**
     * Authenticate.
     *
     * Test that header can be authenticated with realm and authentication info will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Authenticator::addRealm()
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Authenticator::authenticate()
     */
    public function testAuthenticate(): void
    {
        $header = $this->createMock(HeaderInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->once())
            ->method('canAuthenticate')
            ->with($header)
            ->willReturn(true);

        $realm
            ->expects($this->once())
            ->method('authenticate')
            ->with($header)
            ->willReturn($identity);

        /**
         * @var RealmInterface $realm
         * @var HeaderInterface $header
         */
        $authenticator = new Authenticator();
        $authenticated = $authenticator
            ->addRealm($realm)
            ->authenticate($header);

        $this->assertSame($identity, $authenticated);
    }

    /**
     * Fallback realm.
     *
     * Test that both realms can authenticate header, but only the second has any authentication information.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Authenticator::addRealm()
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Authenticator::authenticate()
     */
    public function testFallbackRealm(): void
    {
        $header = $this->createMock(HeaderInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->exactly(2))
            ->method('canAuthenticate')
            ->with($header)
            ->willReturn(true);

        $realm
            ->expects($this->exactly(2))
            ->method('authenticate')
            ->with($header)
            ->willReturnOnConsecutiveCalls(
                null,
                $identity
            );

        /**
         * @var RealmInterface $realm
         * @var HeaderInterface $header
         */
        $authenticator = new Authenticator();
        $authenticated = $authenticator
            ->addRealm($realm)
            ->addRealm($realm)
            ->authenticate($header);

        $this->assertSame($identity, $authenticated);
    }

    /**
     * Authentication failure.
     *
     * Test that when a realm throws an exception the next realm will not be called. For example when credentials are
     * invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Authenticator::addRealm()
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Authenticator::authenticate()
     */
    public function testAuthenticationFailure(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $header = $this->createMock(HeaderInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->once())
            ->method('canAuthenticate')
            ->with($header)
            ->willReturn(true);

        $realm
            ->expects($this->once())
            ->method('authenticate')
            ->with($header)
            ->willThrowException(new InvalidArgumentException());

        /**
         * @var RealmInterface $realm
         * @var HeaderInterface $header
         */
        $authenticator = new Authenticator();
        $authenticator
            ->addRealm($realm)
            ->addRealm($realm)
            ->authenticate($header);
    }

    /**
     * Authentication not supported.
     *
     * Test that no realm can authenticate header and null will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Authenticator::addRealm()
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Authenticator::authenticate()
     */
    public function testAuthenticationNotSupported(): void
    {
        $header = $this->createMock(HeaderInterface::class);

        /**
         * @var HeaderInterface $header
         */
        $authenticator = new Authenticator();
        $this->assertNull($authenticator->authenticate($header));
    }
}
