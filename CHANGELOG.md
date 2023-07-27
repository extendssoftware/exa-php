# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/) and this project adheres to
[Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Added internal permission cache for Authorizer::isPermitted() to not call RealmInterface::getPermissions() twice.
- Added RouteMatchInterface as type to RouterInterface::assemble() name argument.
- Added ServiceLocatorInterface::class key to service locator to get service locator from itself.
- Added default value parameter to RouteMatchInterface::getParameter() method to return when no value found for name.
- Added Executor::execute() parameter lookup inside request attributes.
- Added ContainerInterface::find() to find a value for path, returns default when not found.
- Added default option for MultipleChoicePrompt::prompt() to return when no input is given.
- Added ContainerInterface::class support to ReflectionResolver::getService() to get service locator container.
- Added ParameterValueNotFound::getName() to get parameter name for missing value.
- Added ProxyValidator to proxy value and context to inner validator.
- Added object support to FlattenerInterface::flatten() method.
- Added NoTagsValidator to validate string without HTML-tags.
- Added SpecificationInterface and implementations to use for specification pattern.
- Added allowed array values to invalid validator result parameters.
- Added ValidValidator that will always return a valid result.
- Added ValidatorProviderInterface for a class to act as a validator provider.
- Added ConstraintValidator to validate if iterable values are allowed by array with constraints.
- Added property to LengthValidator to disallow new line characters.
- Added UrlValidator to validate URL string.
- Added Logger::addDecorator() method.
- Added RequestInterface::getId() to get unique ID for each request.

### Changed

- Changed permission name from RouteMatchInterface::getName() to route parameter to allow more control.
- Changed config based router to simpler attribute based router.
- Changed RequestInterface::getMethod() return value from string to enum.
- Changed RouteMatchInterface::getParameter() return value from ?string to mixed.
- Changed middleware order to log and catch exceptions from every other middleware.
- Changed that invalid request body will default to NULL and no exception will be thrown.
- Changed ContainerInterface::get to thrown exception when value not found for path.
- Changed default output verbosity from 1 to 0 so that verbosity corresponds to the amount if -v flags.
- Changed that PosixOutput::getFormatter() will return a cloned formatter for us ability.
- Changed that resource link contains uri and the current request will be used for resource expanding.
- Changed middleware order for HATEOAS middleware to have a identified request.
- Changed optional object property validator from array with added boolean value to ProxyValidator.
- Changed BetweenValidator internal value validator from numeric to integer.

### Deprecated

### Removed

- Removed HATEOAS collection builder.
- Removed ArrayAccess, IteratorAggregate and JsonSerializable interfaces from ContainerInterface.
- Removed InvalidRequestBody exception.
- Removed AbstractWriter::addDecorator() method.
- Removed logger referenced exception.

### Fixed

- Fixed default values for FileWriter::factory() to allow nullable config parameter arguments.
- Fixed that ServiceLocator::getService() will not return a shared service when parameter extra is other than null.
- Fixed that PosixInput::__construct() uses STDIN as default stream.
- Fixed that PosixOutput::__construct() uses STDOUT as default stream.
- Fixed that ShellFactory::create() uses service locator to get descriptor, parser and suggester.
- Fixed that ShellBuilder::build() can expand singular and non-singular links.
- Fixed that MultipleChoicePrompt::prompt() will not continue on invalid option.
- Fixed that PosixInput::character() reads the whole line and only uses the first character.
- Fixed that array is allowed in route definition.
- Fixed that log writer interrupt flag can be passed in config with writer options.

### Security

## [0.1.0] - 2022-12-30

### Added

- Initial version.

[unreleased]: https://github.com/extendssoftware/exa-php/compare/0.1.0...HEAD

[0.1.0]: https://github.com/extendssoftware/exa-php/commits/0.1.0
