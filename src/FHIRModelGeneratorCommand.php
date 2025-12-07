<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Ask;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressIndicator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

#[AsCommand(name: 'fhir:generate', description: 'Generates FHIR model classes from FHIR definitions.')]
readonly class FHIRModelGeneratorCommand
{
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
        private Filesystem     $filesystem,
        private BuilderContext $context,
        private PackageLoader  $packageLoader,
    ) {
    }

    public function __invoke(
        OutputInterface $output,
        #[Option(description: 'Implementation Guide packages to include.', name: 'package', suggestedValues: self::DEFAULT_IG_PACKAGES)]
        #[Ask(question: 'Which FHIR Implementation Guide packages do you want to include?')]
        array           $packages = self::DEFAULT_IG_PACKAGES['R4B'],
    ): int {
        $loadingPackagesIndicator = new ProgressIndicator($output);
        $loadingPackagesIndicator->start('Loading FHIR Implementation Guide packages...');
        foreach ($packages as $package) {
            $version = explode('#', $package)[1] ?? null;
            $loadingPackagesIndicator->setMessage('Loading package ' . $package . ($version ? " version $version" : ''));
            $this->packageLoader->loadPackage($package, $version);
            $loadingPackagesIndicator->advance();
        }

        $loadingPackagesIndicator->finish('Finished loading FHIR Implementation Guide packages.');
        $definitionFiles = [
            'types'     => 'profiles-types.json',
            'enums'     => 'valuesets.json',
            'resources' => 'profiles-resource.json',
        ];

        $targetNamespace  = "Ardenexal\\FHIRTools\\$version\\Element";
        $elementNamespace = new PhpNamespace($targetNamespace);
        $this->context->addElementNamespace($version, $elementNamespace);

        $targetNamespace = "Ardenexal\\FHIRTools\\$version\\Enum";
        $enumNamespace   = new PhpNamespace($targetNamespace);
        $this->context->addEnumNamespace($version, $enumNamespace);

        $this->loadDefinitions($output, $version);

        $this->buildElementClasses($output, $version, $definitionFiles['types'], $elementNamespace);


        $this->buildEnumsForValuesSets($output, $version, $definitionFiles['enums']);
        $this->outputFiles($output, $version);

        return Command::SUCCESS;
    }

    /**
     * @param OutputInterface $output
     * @param string          $version
     * @param string          $types1
     * @param PhpNamespace    $namespace
     *
     * @return void
     *
     * @throws \JsonException
     */
    public function buildElementClasses(OutputInterface $output, string $version, string $types1, PhpNamespace $namespace): void
    {
        $output->writeln('Generating model classes...');

        foreach ($this->context->getDefinitions() as $structureDefinition) {
            if ($structureDefinition['resourceType'] !== 'StructureDefinition') {
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
     *
     * @return void
     * @throws \JsonException
     */
    private function loadDefinitions(OutputInterface $output, string $version): void
    {
        $output->writeln('Loading base elements into context');
        $structureDefinitionBundle = file_get_contents(Path::canonicalize(__DIR__ . "/../resources/definitions/FHIR/{$version}/profiles-types.json"));
        if ($structureDefinitionBundle === false) {
            throw new \RuntimeException("Failed to load FHIR structure definition bundle for version {$version}");
        }
        $structureDefinitionBundle = json_decode($structureDefinitionBundle, true, 512, JSON_THROW_ON_ERROR);

        foreach ($structureDefinitionBundle['entry'] as $type) {
            $this->context->addDefinition($type['fullUrl'], $type['resource']);
        }

        $output->writeln('Loading resources into context');
        $structureDefinitionBundle = file_get_contents(Path::canonicalize(__DIR__ . "/../resources/definitions/FHIR/{$version}/profiles-resources.json"));
        if ($structureDefinitionBundle === false) {
            throw new \RuntimeException("Failed to load FHIR structure definition bundle for version {$version}");
        }
        $structureDefinitionBundle = json_decode($structureDefinitionBundle, true, 512, JSON_THROW_ON_ERROR);

        foreach ($structureDefinitionBundle['entry'] as $type) {
            $this->context->addDefinition($type['fullUrl'], $type['resource']);
        }

        $output->writeln('Loading value sets into context');
        $valuesets = file_get_contents(Path::canonicalize(__DIR__ . "/../resources/definitions/FHIR/{$version}/valuesets.json"));
        if ($valuesets === false) {
            throw new \RuntimeException("Failed to load FHIR value sets for version {$version}");
        }
        $valuesets = json_decode($valuesets, true);
        foreach ($valuesets['entry'] as $valueset) {
            $this->context->addDefinition($valueset['fullUrl'], $valueset['resource']);
        }


    }

    /**
     * @param OutputInterface $output
     * @param string          $version
     * @param string          $valueSetJsonFile
     *
     * @return void
     */
    private function buildEnumsForValuesSets(OutputInterface $output, string $version, string $valueSetJsonFile): void
    {
        $output->writeln('Generating Enums for value sets');

        foreach ($this->context->getPendingEnums() as $pendingEnum) {
            $valueset = $this->context->getDefinition($pendingEnum);
            $url      = $valueset['resource']['url'];

            if (isset($valueset['resource']['version'])) {
                $url .= '|' . $valueset['resource']['version'];
            }
            if ($this->context->hasPendingType($url) === false) {
                continue;
            }
            $valueSet = $valueset['resource'];

            $output->writeln("Generating enum for {$valueSet['name']}");
            $enumGenerator  = new FHIRValueSetGenerator($this->context);
            $classGenerator = new FHIRModelGenerator($this->context);

            $enumType = $enumGenerator->generateEnum($valueset['resource'], $version);
            $this->context->getEnumNamespace($version)->add($enumType);
            $this->context->addEnum($valueSet['url'], $enumType);

            $codeType = $classGenerator->generateModelCodeType($enumType, $version);
            $this->context->addType($valueSet['url'], $codeType);
            $this->context->removePendingType($valueset['resource']['url']);
            $this->context->removePendingType($valueSet['url']);

            $this->context->getElementNamespace($version)->add($codeType);
        }
    }
}
