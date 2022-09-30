<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell;

use ExtendsSoftware\ExaPHP\Shell\Descriptor\DescriptorInterface;
use ExtendsSoftware\ExaPHP\Shell\Parser\ParserInterface;
use ExtendsSoftware\ExaPHP\Shell\Suggester\SuggesterInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ShellBuilderTest extends TestCase
{
    /**
     * Build.
     *
     * Test that builder will build and return a shell.
     *
     * @covers \ExtendsSoftware\ExaPHP\Shell\ShellBuilder::setName()
     * @covers \ExtendsSoftware\ExaPHP\Shell\ShellBuilder::setProgram()
     * @covers \ExtendsSoftware\ExaPHP\Shell\ShellBuilder::setVersion()
     * @covers \ExtendsSoftware\ExaPHP\Shell\ShellBuilder::setDescriptor()
     * @covers \ExtendsSoftware\ExaPHP\Shell\ShellBuilder::setParser()
     * @covers \ExtendsSoftware\ExaPHP\Shell\ShellBuilder::setSuggester()
     * @covers \ExtendsSoftware\ExaPHP\Shell\ShellBuilder::addCommand()
     * @covers \ExtendsSoftware\ExaPHP\Shell\ShellBuilder::build()
     * @covers \ExtendsSoftware\ExaPHP\Shell\ShellBuilder::reset()
     */
    public function testBuild(): void
    {
        $suggester = $this->createMock(SuggesterInterface::class);
        $descriptor = $this->createMock(DescriptorInterface::class);
        $parser = $this->createMock(ParserInterface::class);

        /**
         * @var SuggesterInterface  $suggester
         * @var DescriptorInterface $descriptor
         * @var ParserInterface     $parser
         */
        $builder = new ShellBuilder();
        $shell = $builder
            ->setName('Acme console')
            ->setProgram('acme')
            ->setVersion('1.0')
            ->setDescriptor($descriptor)
            ->setParser($parser)
            ->setSuggester($suggester)
            ->addCommand('do.task', 'Do some fancy task!', [
                [
                    'name' => 'first_name',
                ],
            ], [
                [
                    'name' => 'force',
                    'description' => 'Force things!',
                    'short' => 'f',
                ],
            ], [
                'task' => stdClass::class,
            ])
            ->build();

        $this->assertIsObject($shell);
    }

    /**
     * Build shell without commands.
     *
     * Test that builder will build and return a shell without commands.
     *
     * @covers \ExtendsSoftware\ExaPHP\Shell\ShellBuilder::build()
     * @covers \ExtendsSoftware\ExaPHP\Shell\ShellBuilder::reset()
     */
    public function testBuildWithoutCommand(): void
    {
        $builder = new ShellBuilder();
        $shell = $builder->build();

        $this->assertIsObject($shell);
    }
}
