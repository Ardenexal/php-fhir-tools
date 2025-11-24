<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

#[AsCommand(name: 'fhir:generate-models', description: 'Generates FHIR model classes from FHIR definitions.')]
readonly class FHIRModelGeneratorCommand
{
    public function __construct(
        private Filesystem $filesystem,
        private BuilderContext $context
    ) {
    }

    public function __invoke(
        #[Argument(description: 'Select FHIR version to generate model classes for.', suggestedValues: ['R4', 'R4B', 'R5'])]
        string $version,
        OutputInterface $output
    ): int {
        $definitionFiles = [
            'types'     => 'profiles-types.json',
            'enums'     => 'valuesets.json',
            'resources' => 'profiles-resource.json',
        ];

        $targetNamespace  = "Ardenexal\\FhirTools\\$version\\Element";
        $elementNamespace = new PhpNamespace($targetNamespace);
        $this->context->addElementNamespace($version, $elementNamespace);

        $targetNamespace = "Ardenexal\\FhirTools\\$version\\Enum";
        $enumNamespace   = new PhpNamespace($targetNamespace);
        $this->context->addEnumNamespace($version, $enumNamespace);


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
        $structureDefinitionBundle = file_get_contents(Path::canonicalize(__DIR__ . "/../resources/definitions/FHIR/{$version}/{$types1}"));
        if ($structureDefinitionBundle === false) {
            throw new \RuntimeException("Failed to load FHIR structure definition bundle for version {$version}");
        }
        $structureDefinitionBundle = json_decode($structureDefinitionBundle, true, 512, JSON_THROW_ON_ERROR);

        foreach ($structureDefinitionBundle['entry'] as $type) {
            $structureDefinition = $type['resource'];
            $this->context->addDefinition($structureDefinition['url'], $structureDefinition);
            $output->writeln("Generating model class for {$structureDefinition['name']}");
            $generator = new FHIRModelGenerator($this->context);

            $class     = $generator->generateModelClass($structureDefinition, $version);
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
            $classContents = self::asPhpFile($type, $this->context->getElementNamespace($version));
            $path          = Path::canonicalize(__DIR__ . '/../output/' . $type->getNamespace()?->getName() . '/' . $type->getName() . '.php');
            $this->filesystem->dumpFile($path, $classContents);
            $output->writeln("Generated model class for {$type->getName()}");
        }

        foreach ($this->context->getEnums() as $type) {
            $classContents = self::asPhpFile($type, $this->context->getEnumNamespace($version));
            $path          = Path::canonicalize(__DIR__ . '/../output/' . $type->getNamespace()?->getName() . '/' . $type->getName() . '.php');
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
    private function buildEnumsForValuesSets(OutputInterface $output, string $version, string $valueSetJsonFile): void
    {
        $output->writeln('Generating Enums for value sets');
        $valuesets = file_get_contents(Path::canonicalize(__DIR__ . "/../resources/definitions/FHIR/{$version}/{$valueSetJsonFile}"));
        if ($valuesets === false) {
            throw new \RuntimeException("Failed to load FHIR value sets for version {$version}");
        }
        $valuesets = json_decode($valuesets, true);
        foreach ($valuesets['entry'] as $valueset) {
            $url = $valueset['resource']['url'];

            $this->context->addDefinition($valueset['resource']['url'], $valueset['resource']);
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

            $enumType       = $enumGenerator->generateEnum($valueset['resource'], $version);
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
