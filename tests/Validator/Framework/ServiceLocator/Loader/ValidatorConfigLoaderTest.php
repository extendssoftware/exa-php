<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\Boolean\FalseValidator;
use ExtendsSoftware\ExaPHP\Validator\Boolean\TrueValidator;
use ExtendsSoftware\ExaPHP\Validator\Collection\ContainsValidator;
use ExtendsSoftware\ExaPHP\Validator\Collection\InArrayValidator;
use ExtendsSoftware\ExaPHP\Validator\Collection\SizeValidator;
use ExtendsSoftware\ExaPHP\Validator\Comparison\EqualValidator;
use ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterOrEqualValidator;
use ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterThanValidator;
use ExtendsSoftware\ExaPHP\Validator\Comparison\IdenticalValidator;
use ExtendsSoftware\ExaPHP\Validator\Comparison\LessOrEqualValidator;
use ExtendsSoftware\ExaPHP\Validator\Comparison\LessThanValidator;
use ExtendsSoftware\ExaPHP\Validator\Comparison\NotEqualValidator;
use ExtendsSoftware\ExaPHP\Validator\Comparison\NotIdenticalValidator;
use ExtendsSoftware\ExaPHP\Validator\Framework\ServiceLocator\Factory\Validator\ValidatorFactory;
use ExtendsSoftware\ExaPHP\Validator\Logical\AndValidator;
use ExtendsSoftware\ExaPHP\Validator\Logical\NotValidator;
use ExtendsSoftware\ExaPHP\Validator\Logical\OrValidator;
use ExtendsSoftware\ExaPHP\Validator\Logical\XorValidator;
use ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator;
use ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator;
use ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate\LatitudeValidator;
use ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate\LongitudeValidator;
use ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\CoordinatesValidator;
use ExtendsSoftware\ExaPHP\Validator\Other\NullableValidator;
use ExtendsSoftware\ExaPHP\Validator\Text\DateTimeValidator;
use ExtendsSoftware\ExaPHP\Validator\Text\EmailAddressValidator;
use ExtendsSoftware\ExaPHP\Validator\Text\IpAddressValidator;
use ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator;
use ExtendsSoftware\ExaPHP\Validator\Text\NotEmptyValidator;
use ExtendsSoftware\ExaPHP\Validator\Text\RegexValidator;
use ExtendsSoftware\ExaPHP\Validator\Text\UuidValidator;
use ExtendsSoftware\ExaPHP\Validator\Type\ArrayValidator;
use ExtendsSoftware\ExaPHP\Validator\Type\BooleanValidator;
use ExtendsSoftware\ExaPHP\Validator\Type\FloatValidator;
use ExtendsSoftware\ExaPHP\Validator\Type\IntegerValidator;
use ExtendsSoftware\ExaPHP\Validator\Type\IterableValidator;
use ExtendsSoftware\ExaPHP\Validator\Type\NullValidator;
use ExtendsSoftware\ExaPHP\Validator\Type\NumberValidator;
use ExtendsSoftware\ExaPHP\Validator\Type\NumericValidator;
use ExtendsSoftware\ExaPHP\Validator\Type\ObjectValidator;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class ValidatorConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that load method returns correct config.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Framework\ServiceLocator\Loader\ValidatorConfigLoader::load
     */
    public function testLoad(): void
    {
        $loader = new ValidatorConfigLoader();

        $this->assertSame([
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    ValidatorInterface::class => ValidatorFactory::class,
                ],
                StaticFactoryResolver::class => [
                    // Boolean
                    FalseValidator::class => FalseValidator::class,
                    TrueValidator::class => TrueValidator::class,
                    // Collection
                    ContainsValidator::class => ContainsValidator::class,
                    InArrayValidator::class => InArrayValidator::class,
                    SizeValidator::class => SizeValidator::class,
                    // Comparison
                    EqualValidator::class => EqualValidator::class,
                    GreaterOrEqualValidator::class => GreaterOrEqualValidator::class,
                    GreaterThanValidator::class => GreaterThanValidator::class,
                    IdenticalValidator::class => IdenticalValidator::class,
                    LessOrEqualValidator::class => LessOrEqualValidator::class,
                    LessThanValidator::class => LessThanValidator::class,
                    NotEqualValidator::class => NotEqualValidator::class,
                    NotIdenticalValidator::class => NotIdenticalValidator::class,
                    // Logical
                    AndValidator::class => AndValidator::class,
                    NotValidator::class => NotValidator::class,
                    OrValidator::class => OrValidator::class,
                    XorValidator::class => XorValidator::class,
                    // Number
                    BetweenValidator::class => BetweenValidator::class,
                    // Object
                    PropertiesValidator::class => PropertiesValidator::class,
                    // Other
                    CoordinatesValidator::class => CoordinatesValidator::class,
                    LatitudeValidator::class => LatitudeValidator::class,
                    LongitudeValidator::class => LongitudeValidator::class,
                    NullableValidator::class => NullableValidator::class,
                    // Text
                    DateTimeValidator::class => DateTimeValidator::class,
                    EmailAddressValidator::class => EmailAddressValidator::class,
                    IpAddressValidator::class => IpAddressValidator::class,
                    LengthValidator::class => LengthValidator::class,
                    NotEmptyValidator::class => NotEmptyValidator::class,
                    RegexValidator::class => RegexValidator::class,
                    UuidValidator::class => UuidValidator::class,
                    // Type
                    ArrayValidator::class => ArrayValidator::class,
                    BooleanValidator::class => BooleanValidator::class,
                    FloatValidator::class => FloatValidator::class,
                    IntegerValidator::class => IntegerValidator::class,
                    IterableValidator::class => IterableValidator::class,
                    NullValidator::class => NullValidator::class,
                    NumberValidator::class => NumberValidator::class,
                    NumericValidator::class => NumericValidator::class,
                    ObjectValidator::class => ObjectValidator::class,
                    StringValidator::class => StringValidator::class,
                ],
            ],
        ], $loader->load());
    }
}
