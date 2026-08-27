<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Command;

use Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRIGGeneratorCommand;
use Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRModelGeneratorCommand;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Guards both generator commands against regressing to Symfony's invokable-command style.
 *
 * composer.json declares `symfony/console: ^6.4|^7.4`. The invokable style needs `__invoke()`
 * plus parameter attributes that 6.4 does not ship: `#[Option]` and `#[Argument]` arrived in
 * 7.3 and `#[Ask]` in 7.4.
 *
 * On 6.4 PHP never reflects those attributes, so the breakage is silent at load time and total
 * at invoke time: options go unregistered and Command::execute() throws. These assertions run
 * on any console version, so a reintroduction fails on a 7.x machine without waiting for CI.
 *
 * @covers \Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRIGGeneratorCommand
 * @covers \Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRModelGeneratorCommand
 */
final class SymfonyConsoleCompatibilityTest extends TestCase
{
    /**
     * Console attribute classes absent from symfony/console 6.4. Referencing any of them from a
     * command silently drops the corresponding CLI option on that version.
     */
    private const array POST_64_ATTRIBUTES = [
        'Symfony\Component\Console\Attribute\Option',
        'Symfony\Component\Console\Attribute\Argument',
        'Symfony\Component\Console\Attribute\Ask',
    ];

    /**
     * @return iterable<string, array{Command}>
     */
    public static function commandProvider(): iterable
    {
        $filesystem = new Filesystem();
        $loader     = (new \ReflectionClass(PackageLoader::class))->newInstanceWithoutConstructor();

        yield 'fhir:generate'    => [new FHIRModelGeneratorCommand($filesystem, $loader)];
        yield 'fhir:generate-ig' => [new FHIRIGGeneratorCommand($filesystem, $loader)];
    }

    #[DataProvider('commandProvider')]
    public function testCommandOverridesExecute(Command $command): void
    {
        $execute = new \ReflectionMethod($command, 'execute');

        self::assertSame(
            $command::class,
            $execute->getDeclaringClass()->getName(),
            'execute() must be overridden on the command itself; Command::execute() throws a LogicException.',
        );
    }

    #[DataProvider('commandProvider')]
    public function testCommandIsNotInvokable(Command $command): void
    {
        self::assertFalse(
            method_exists($command, '__invoke'),
            '__invoke() is only dispatched from symfony/console 7.3 onwards.',
        );
    }

    #[DataProvider('commandProvider')]
    public function testCommandAvoidsAttributesMissingFromSymfony64(Command $command): void
    {
        $file = (new \ReflectionClass($command))->getFileName();
        self::assertIsString($file);

        $source = file_get_contents($file);
        self::assertIsString($source);

        foreach (self::POST_64_ATTRIBUTES as $attribute) {
            self::assertStringNotContainsString(
                $attribute,
                $source,
                \sprintf('%s does not exist in symfony/console 6.4.', $attribute),
            );
        }
    }

    /**
     * Both commands expose the same repeatable, order-preserving --package option and the same
     * --offline flag. The modes matter: dropping VALUE_IS_ARRAY would silently collapse repeated
     * --package arguments to the last one given.
     */
    #[DataProvider('commandProvider')]
    public function testPackageAndOfflineOptionsAreRegistered(Command $command): void
    {
        $definition = $command->getDefinition();

        self::assertSame(
            ['package', 'offline'],
            array_keys($definition->getOptions()),
            'Option names and order form part of the documented CLI surface.',
        );

        $package = $definition->getOption('package');
        self::assertTrue($package->isArray(), '--package must be repeatable.');
        self::assertTrue($package->isValueRequired(), '--package must take a value.');
        self::assertNull($package->getShortcut());

        $offline = $definition->getOption('offline');
        self::assertFalse($offline->acceptValue(), '--offline must be a flag.');
        self::assertFalse($offline->isNegatable());
        self::assertFalse($offline->getDefault());
    }

    public function testModelGeneratorPackageOptionDefaultsToR4(): void
    {
        $command = new FHIRModelGeneratorCommand(
            new Filesystem(),
            (new \ReflectionClass(PackageLoader::class))->newInstanceWithoutConstructor(),
        );

        self::assertSame(
            [
                'hl7.terminology.r4#7.0.0',
                'hl7.fhir.r4.core#4.0.1',
                'hl7.fhir.uv.extensions.r4#5.2.0',
            ],
            $command->getDefinition()->getOption('package')->getDefault(),
        );
    }

    public function testIgGeneratorPackageOptionDefaultsToEmptyList(): void
    {
        $command = new FHIRIGGeneratorCommand(
            new Filesystem(),
            (new \ReflectionClass(PackageLoader::class))->newInstanceWithoutConstructor(),
        );

        // Empty rather than a package set: an omitted --package falls back to fhir.ig.packages.
        self::assertSame([], $command->getDefinition()->getOption('package')->getDefault());
    }

    /**
     * Sanity check on the mode constants the commands pass, so
     * testPackageAndOfflineOptionsAreRegistered cannot be satisfied by an option that merely
     * happens to look right.
     */
    public function testPackageOptionUsesArrayAndRequiredValueModes(): void
    {
        $command = new FHIRIGGeneratorCommand(
            new Filesystem(),
            (new \ReflectionClass(PackageLoader::class))->newInstanceWithoutConstructor(),
        );

        $expected = new InputOption(
            'package',
            null,
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            $command->getDefinition()->getOption('package')->getDescription(),
            [],
        );

        self::assertTrue($expected->equals($command->getDefinition()->getOption('package')));
    }
}
