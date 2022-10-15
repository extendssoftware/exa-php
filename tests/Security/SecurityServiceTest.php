<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Security;

use ExtendsSoftware\ExaPHP\Authentication\AuthenticatorInterface;
use ExtendsSoftware\ExaPHP\Authentication\Header\HeaderInterface;
use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use PHPUnit\Framework\TestCase;

class SecurityServiceTest extends TestCase
{
    /**
     * Authenticate.
     *
     * Test that authenticator will authenticate given header.
     *
     * @covers \ExtendsSoftware\ExaPHP\Security\SecurityService::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Security\SecurityService::authenticate()
     * @covers \ExtendsSoftware\ExaPHP\Security\SecurityService::getIdentity()
     */
    public function testAuthenticate(): void
    {
        $header = $this->createMock(HeaderInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        $authenticator = $this->createMock(AuthenticatorInterface::class);
        $authenticator
            ->expects($this->exactly(2))
            ->method('authenticate')
            ->with($header)
            ->willReturnOnConsecutiveCalls($identity, null);

        $authorizer = $this->createMock(AuthorizerInterface::class);

        /**
         * @var AuthenticatorInterface $authenticator
         * @var AuthorizerInterface    $authorizer
         * @var HeaderInterface        $header
         */
        $service = new SecurityService($authenticator, $authorizer);

        $this->assertTrue($service->authenticate($header));
        $this->assertIsObject($service->getIdentity());
        $this->assertFalse($service->authenticate($header));
    }

    /**
     * Authorizer methods.
     *
     * Test that correct authorizer methods will be called.
     *
     * @covers \ExtendsSoftware\ExaPHP\Security\SecurityService::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Security\SecurityService::isPermitted()
     * @covers \ExtendsSoftware\ExaPHP\Security\SecurityService::isAllowed()
     */
    public function testAuthorizerMethods(): void
    {
        $authenticator = $this->createMock(AuthenticatorInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        $policy = $this->createMock(PolicyInterface::class);

        $authorizer = $this->createMock(AuthorizerInterface::class);
        $authorizer
            ->expects($this->once())
            ->method('isPermitted')
            ->with(
                $this->isInstanceOf(PermissionInterface::class),
                $identity
            )
            ->willReturn(true);

        $authorizer
            ->expects($this->once())
            ->method('isAllowed')
            ->with($policy, $identity)
            ->willReturn(true);

        /**
         * @var AuthenticatorInterface $authenticator
         * @var AuthorizerInterface    $authorizer
         * @var PolicyInterface        $policy
         */
        $service = new SecurityService($authenticator, $authorizer, $identity);

        $this->assertTrue($service->isPermitted('foo:bar:baz'));
        $this->assertTrue($service->isAllowed($policy));
    }

    /**
     * Identity not found.
     *
     * Test that null will be returned when identity is not found.
     *
     * @covers \ExtendsSoftware\ExaPHP\Security\SecurityService::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Security\SecurityService::getIdentity()
     * @covers \ExtendsSoftware\ExaPHP\Security\SecurityService::isPermitted()
     * @covers \ExtendsSoftware\ExaPHP\Security\SecurityService::isAllowed()
     */
    public function testIdentityNotFound(): void
    {
        $authenticator = $this->createMock(AuthenticatorInterface::class);

        $authorizer = $this->createMock(AuthorizerInterface::class);

        $policy = $this->createMock(PolicyInterface::class);

        /**
         * @var AuthenticatorInterface $authenticator
         * @var AuthorizerInterface    $authorizer
         * @var PolicyInterface        $policy
         */
        $service = new SecurityService($authenticator, $authorizer);

        $this->assertNull($service->getIdentity());
        $this->assertFalse($service->isPermitted('foo:bar:baz'));
        $this->assertFalse($service->isAllowed($policy));
    }
}
