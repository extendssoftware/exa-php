<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity\Storage\InMemory;

use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use ExtendsSoftware\ExaPHP\Identity\Storage\Exception\IdentityNotSet;
use PHPUnit\Framework\TestCase;

class InMemoryStorageTest extends TestCase
{
    /**
     * Identity.
     *
     * Test that set identity will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Identity\Storage\InMemory\InMemoryStorage::setIdentity()
     * @covers \ExtendsSoftware\ExaPHP\Identity\Storage\InMemory\InMemoryStorage::getIdentity()
     */
    public function testIdentity(): void
    {
        $identity = $this->createMock(IdentityInterface::class);

        /**
         * @var IdentityInterface $identity
         */
        $storage = new InMemoryStorage();
        $stored = $storage
            ->setIdentity($identity)
            ->getIdentity();

        $this->assertSame($stored, $identity);
    }

    /**
     * Empty identity.
     *
     * Test that an exception will be thrown when identity is not set.
     *
     * @covers \ExtendsSoftware\ExaPHP\Identity\Storage\InMemory\InMemoryStorage::getIdentity()
     * @covers \ExtendsSoftware\ExaPHP\Identity\Storage\Exception\IdentityNotSet::__construct()
     */
    public function testEmptyIdentity(): void
    {
        $this->expectException(IdentityNotSet::class);
        $this->expectExceptionMessage('Identity not set.');

        $storage = new InMemoryStorage();
        $storage->getIdentity();
    }
}
