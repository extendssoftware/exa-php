<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class VisibleValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consist of visible printable characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\VisibleValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new VisibleValidator();

        $this->assertTrue($validator->validate('arf12')->isValid());
        $this->assertTrue($validator->validate('LKA#@%.54')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of visible printable characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\VisibleValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\VisibleValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new VisibleValidator();

        $this->assertFalse($validator->validate("asdf\n\r\t")->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\VisibleValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new VisibleValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns an instanceof of ValidatorInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\VisibleValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = VisibleValidator::factory(VisibleValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
