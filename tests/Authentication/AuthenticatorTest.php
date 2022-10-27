<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication;

use ExtendsSoftware\ExaPHP\Authentication\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
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
        $request = $this->createMock(RequestInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->once())
            ->method('canAuthenticate')
            ->with($request)
            ->willReturn(true);

        $realm
            ->expects($this->once())
            ->method('authenticate')
            ->with($request)
            ->willReturn($identity);

        /**
         * @var RealmInterface   $realm
         * @var RequestInterface $request
         */
        $authenticator = new Authenticator();
        $authenticated = $authenticator
            ->addRealm($realm)
            ->authenticate($request);

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

        $request = $this->createMock(RequestInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->once())
            ->method('canAuthenticate')
            ->with($request)
            ->willReturn(true);

        $realm
            ->expects($this->once())
            ->method('authenticate')
            ->with($request)
            ->willThrowException(new InvalidArgumentException());

        /**
         * @var RealmInterface   $realm
         * @var RequestInterface $request
         */
        $authenticator = new Authenticator();
        $authenticator
            ->addRealm($realm)
            ->addRealm($realm)
            ->authenticate($request);
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
        $request = $this->createMock(RequestInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->once())
            ->method('canAuthenticate')
            ->with($request)
            ->willReturn(false);

        /**
         * @var RequestInterface $request
         * @var RealmInterface   $realm
         */
        $authenticator = new Authenticator();
        $this->assertNull($authenticator->authenticate($request));

        $authenticator->addRealm($realm);
        $this->assertNull($authenticator->authenticate($request));
    }
}
