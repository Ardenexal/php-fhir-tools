<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Context;

use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Central context for managing FHIR code generation state
 *
 * This class serves as the central repository for all generated types,
 * namespaces, and FHIR definitions during the code generation process.
 * It manages:
 *
 * - Namespace organization by FHIR version
 * - Generated class and enum storage
 * - FHIR definition caching and retrieval
 * - Pending type resolution tracking
 * - Resource and type relationship management
 *
 * The BuilderContext ensures that all generated code is properly organized
 * and that dependencies between types are correctly resolved.
 *
 * @author FHIR Tools
 * @since 1.0.0
 */
class BuilderContext implements BuilderContextInterface
{
    /**
     * Default prefix for all generated FHIR classes
     */
    public const DEFAULT_CLASS_PREFIX = 'FHIR';

    /**
     * Namespaces for element classes organized by FHIR version
     *
     * @var array<string, PhpNamespace>
     */
    private array $elementNamespaces = [];

    /**
     * Namespaces for enum classes organized by FHIR version
     *
     * @var array<string, PhpNamespace>
     */
    private array $enumNamespaces = [];

    /**
     * Raw FHIR definitions loaded from packages, keyed by URL
     *
     * @var array<string, array<string, mixed>>
     */
    private array $definitions = [];

    /**
     * Generated FHIR resource classes, keyed by URL
     *
     * @var array<string, ClassType>
     */
    private array $resources = [];

    /**
     * Generated FHIR type classes, keyed by URL
     *
     * @var array<string, ClassType>
     */
    private array $types = [];

    /**
     * Pending types awaiting generation, keyed by URL with class name values
     *
     * @var array<string, class-string>
     */
    private array $pendingTypes = [];

    /**
     * Generated enum classes, keyed by URL
     *
     * @var array<string, EnumType>
     */
    private array $enums = [];

    /**
     * @var array<string, class-string> - Pending enums to be generated (keyed by valueset/codesystem URL)
     */
    private array $pendingEnums = [];

    public function addResource(string $url, ClassType $resource): void
    {
        $this->resources[$url] = $resource;
    }

    public function getResources(): array
    {
        return $this->resources;
    }

    public function addType(string $url, ClassType $type): void
    {
        $this->types[$url] = $type;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function getType(string $path): ?ClassType
    {
        return $this->types[$path] ?? null;
    }

    public function addEnum(string $url, EnumType $enum): void
    {
        $this->enums[$url] = $enum;
    }

    public function getEnums(): array
    {
        return $this->enums;
    }

    public function getEnum(string $url): ?EnumType
    {
        return $this->enums[$url] ?? null;
    }

    public function addDefinition(string $url, array $definition): void
    {
        $this->definitions[$url] = $definition;
    }

    public function getDefinition(string $url): ?array
    {
        return $this->definitions[$url] ?? null;
    }

    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    public function getElementNamespace(string $version): PhpNamespace
    {
        if (!isset($this->elementNamespaces[$version])) {
            throw GenerationException::missingNamespace($version, 'element');
        }

        return $this->elementNamespaces[$version];
    }

    public function getEnumNamespace(string $version): PhpNamespace
    {
        if (!isset($this->enumNamespaces[$version])) {
            throw GenerationException::missingNamespace($version, 'enum');
        }

        return $this->enumNamespaces[$version];
    }

    public function addElementNamespace(string $version, PhpNamespace $namespace): void
    {
        $this->elementNamespaces[$version] = $namespace;
    }

    public function addEnumNamespace(string $version, PhpNamespace $namespace): void
    {
        $this->enumNamespaces[$version] = $namespace;
    }

    /**
     * @param string       $url
     * @param class-string $enumName
     */
    public function addPendingEnum(string $url, string $enumName): void
    {
        $this->pendingEnums[$url] = $enumName;
    }

    /**
     * @return array<string, class-string>
     */
    public function getPendingEnums(): array
    {
        return $this->pendingEnums;
    }

    /**
     * @param string       $url
     * @param class-string $typeClassName
     */
    public function addPendingType(string $url, string $typeClassName): void
    {
        $url                      = preg_replace('/\|.*$/', '', $url);
        $this->pendingTypes[$url] = $typeClassName;
    }

    /**
     * @return array<string, class-string>
     */
    public function getPendingTypes(): array
    {
        return $this->pendingTypes;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function hasPendingType(string $url): bool
    {
        return array_key_exists($url, $this->pendingTypes);
    }

    /**
     * @param string $url
     *
     * @return void
     */
    public function removePendingType(string $url): void
    {
        unset($this->pendingTypes[$url]);
    }

    /**
     * @param string $valuesetURL
     *
     * @return void
     */
    public function removePendingEnum(string $valuesetURL): void
    {
        unset($this->pendingEnums[$valuesetURL]);
    }
}