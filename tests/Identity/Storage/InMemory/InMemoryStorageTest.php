<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity\Storage\InMemory;

use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
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
     * Test that storage identity property is initialized with a null value.
     *
     * @covers \ExtendsSoftware\ExaPHP\Identity\Storage\InMemory\InMemoryStorage::getIdentity()
     */
    public function testEmptyIdentity(): void
    {
        $storage = new InMemoryStorage();

        $this->assertNull($storage->getIdentity());
    }
}
