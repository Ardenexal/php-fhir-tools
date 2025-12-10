<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Ask;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressIndicator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

use function Symfony\Component\String\s;

#[AsCommand(name: 'fhir:generate', description: 'Generates FHIR model classes from FHIR definitions.')]
readonly class FHIRModelGeneratorCommand
{
    private const string DEFAULT_TERMINOLOGY_PACKAGE = 'hl7.terminology.r4#7.0.0';

    private const array DEFAULT_IG_PACKAGES = [
        'R4'  => [
            'hl7.fhir.r4.core',
        ],
        'R4B' => [
            'hl7.fhir.r4b.core',
        ],
        'R5'  => [
            'hl7.fhir.r5.core',
        ],
    ];

    public function __construct(
        private Filesystem $filesystem,
        private BuilderContext $context,
        private PackageLoader $packageLoader,
    ) {
    }

    public function __invoke(
        OutputInterface $output,
        #[Option(description: 'Implementation Guide packages to include.', name: 'package', suggestedValues: self::DEFAULT_IG_PACKAGES)]
        #[Ask(question: 'Which FHIR Implementation Guide packages do you want to include?')]
        array $packages = self::DEFAULT_IG_PACKAGES['R4B'],
    ): int {
        $loadingPackagesIndicator = new ProgressIndicator($output);
        $loadingPackagesIndicator->start('Loading FHIR Implementation Guide packages...');
        array_unshift($packages, self::DEFAULT_TERMINOLOGY_PACKAGE);
        foreach ($packages as $package) {
            $packageParts = explode('#', $package);
            $version      = $packageParts[1] ?? null;
            $package      = $packageParts[0];
            $loadingPackagesIndicator->setMessage('Loading package ' . $package . ($version ? " version $version" : ''));
            $packageMetaData = $this->packageLoader->installPackage($package, $version);

            $this->generateClassesForPackage($output, $package, $packageMetaData['fhirVersions'][0]);
            //            $this->packageLoader->loadPackageToContext($package, $version);
            $loadingPackagesIndicator->advance();
        }
        $loadingPackagesIndicator->finish('Finished loading FHIR Implementation Guide packages.');


        return Command::SUCCESS;
    }

    /**
     * @param OutputInterface $output
     * @param string          $version
     * @param PhpNamespace    $namespace
     *
     * @return void
     *
     * @throws \JsonException
     */
    public function buildElementClasses(OutputInterface $output, string $version, PhpNamespace $namespace): void
    {
        $output->writeln('Generating model classes...');

        foreach ($this->context->getDefinitions() as $structureDefinition) {
            if ($structureDefinition['resourceType'] !== 'StructureDefinition') {
                continue;
            }

                        // Ignore Profiles that are constraints on other types for now
            if ($structureDefinition['kind'] === 'logical' || (isset($structureDefinition['derivation']) && $structureDefinition['derivation'] === 'constraint')) {
                continue;
            }
            $output->writeln("Generating model class for {$structureDefinition['name']}");
            $generator = new FHIRModelGenerator($this->context);

            $class = $generator->generateModelClass($structureDefinition, $version);
            $this->context->addType($structureDefinition['url'], $class);

            $namespace->add($class);
        }
    }

    /**
     * @param OutputInterface $output
     * @param string          $version
     *
     * @return void
     */
    public function outputFiles(OutputInterface $output, string $version): void
    {
        foreach ($this->context->getTypes() as $type) {
            $elementNamespace = $this->context->getElementNamespace($version);
            $classContents    = self::asPhpFile($type, $elementNamespace);
            $path             = Path::canonicalize(__DIR__ . '/../output/' . $elementNamespace->getName() . '/' . $type->getName() . '.php');
            $this->filesystem->dumpFile($path, $classContents);
            $output->writeln("Generated model class for {$type->getName()}");
        }

        foreach ($this->context->getEnums() as $type) {
            $enumNamespace = $this->context->getEnumNamespace($version);
            $classContents = self::asPhpFile($type, $enumNamespace);
            $path          = Path::canonicalize(__DIR__ . '/../output/' . $enumNamespace->getName() . '/' . $type->getName() . '.php');
            $this->filesystem->dumpFile($path, $classContents);
            $output->writeln("Generated model class for {$type->getName()}");
        }
    }

    /**
     * @param ClassType|EnumType $classType
     * @param PhpNamespace       $namespace
     *
     * @return string
     */
    protected static function asPhpFile(ClassType|EnumType $classType, PhpNamespace $namespace): string
    {
        $printer = new Printer();

        return <<<PHP
        <?php declare(strict_types=1);

        namespace {$namespace->getName()};

        {$printer->printClass($classType, $namespace)}
        PHP;
    }

    /**
     * @param OutputInterface $output
     * @param string          $version
     * @param string          $valueSetJsonFile
     *
     * @return void
     */
    private function buildEnumsForValuesSets(OutputInterface $output, string $version): void
    {
        $output->writeln('Generating Enums for value sets');

        foreach ($this->context->getPendingEnums() as $key => $pendingEnum) {
            $valueset = $this->context->getDefinition($key);

            $url      = $valueset['url'];


            if ($this->context->hasPendingType($url) === false) {
                continue;
            }

            $output->writeln("Generating enum for {$valueset['name']}");

            $enumGenerator  = new FHIRValueSetGenerator($this->context);
            $classGenerator = new FHIRModelGenerator($this->context);

            $enumType = $enumGenerator->generateEnum($valueset, $version);
            $this->context->getEnumNamespace($version)->add($enumType);
            $this->context->addEnum($url, $enumType);

            $codeType = $classGenerator->generateModelCodeType($enumType, $version);
            $this->context->addType($url, $codeType);
            $this->context->removePendingType($url);
            $this->context->removePendingEnum($url);

            $this->context->getElementNamespace($version)->add($codeType);
        }

        if (count($this->context->getPendingTypes()) > 0) {
            foreach ($this->context->getPendingTypes() as $pendingTypeUrl) {
                $output->writeln("Warning: Could not generate type for $pendingTypeUrl");
            }
            throw new \RuntimeException('There are still pending types after enum generation.');
        }
    }

    /**
     * @param OutputInterface $output
         * @param string          $package
     * @param string|null     $version
     *
     * @return void
     *
     * @throws \JsonException
     */
    private function generateClassesForPackage(OutputInterface $output, string $package, ?string $version): void
    {
        $namespaceParts = s($package)->split('.');
        $parts          = [];
        foreach ($namespaceParts as $namespace) {
            $parts[] = $namespace->pascal();
        }
        $namespace    = s('\\')->join($parts);
        $namedVersion = match ($version) {
            '4.0.1' => 'R4',
            '4.3.0' => 'R4B',
            '5.0.0' => 'R5',
            default => throw new \RuntimeException("Unsupported FHIR version: $version"),
        };
        $targetNamespace  = "Ardenexal\\FHIRTools\\$namespace\\Element";
        $elementNamespace = new PhpNamespace($targetNamespace);
        $this->context->addElementNamespace($namedVersion, $elementNamespace);

        $targetNamespace = "Ardenexal\\FHIRTools\\$namespace\\Enum";
        $enumNamespace   = new PhpNamespace($targetNamespace);
        $this->context->addEnumNamespace($namedVersion, $enumNamespace);


        $this->buildElementClasses($output, $namedVersion, $elementNamespace);


        $this->buildEnumsForValuesSets($output, $namedVersion);
        $this->outputFiles($output, $namedVersion);
    }
}
