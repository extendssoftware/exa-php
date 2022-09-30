<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\About;

use PHPUnit\Framework\TestCase;

class AboutTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Shell\About\About::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Shell\About\About::getName()
     * @covers \ExtendsSoftware\ExaPHP\Shell\About\About::getProgram()
     * @covers \ExtendsSoftware\ExaPHP\Shell\About\About::getVersion()
     */
    public function testGetMethods(): void
    {
        $about = new About('ExaPHP Console', 'extends', '0.1');

        $this->assertSame('ExaPHP Console', $about->getName());
        $this->assertSame('extends', $about->getProgram());
        $this->assertSame('0.1', $about->getVersion());
    }
}
