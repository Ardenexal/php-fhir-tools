<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Context;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Interface for managing FHIR code generation context
 *
 * Defines the contract for managing generated types, namespaces,
 * and FHIR definitions during the code generation process.
 *
 * @author FHIR Tools
 * @since 1.0.0
 */
interface BuilderContextInterface
{
    /**
     * Add a generated resource class
     *
     * @param string $url The FHIR resource URL
     * @param ClassType $resource The generated resource class
     */
    public function addResource(string $url, ClassType $resource): void;

    /**
     * Get all generated resource classes
     *
     * @return array<string, ClassType>
     */
    public function getResources(): array;

    /**
     * Add a generated type class
     *
     * @param string $url The FHIR type URL or path
     * @param ClassType $type The generated type class
     */
    public function addType(string $url, ClassType $type): void;

    /**
     * Get all generated type classes
     *
     * @return array<string, ClassType>
     */
    public function getTypes(): array;

    /**
     * Get a specific type by URL or path
     *
     * @param string $path The type path or URL
     * @return ClassType|null The type class or null if not found
     */
    public function getType(string $path): ?ClassType;

    /**
     * Add a generated enum
     *
     * @param string $url The enum URL
     * @param EnumType $enum The generated enum
     */
    public function addEnum(string $url, EnumType $enum): void;

    /**
     * Get all generated enums
     *
     * @return array<string, EnumType>
     */
    public function getEnums(): array;

    /**
     * Get a specific enum by URL
     *
     * @param string $url The enum URL
     * @return EnumType|null The enum or null if not found
     */
    public function getEnum(string $url): ?EnumType;

    /**
     * Add a FHIR definition
     *
     * @param string $url The definition URL
     * @param array<string, mixed> $definition The FHIR definition
     */
    public function addDefinition(string $url, array $definition): void;

    /**
     * Get a FHIR definition by URL
     *
     * @param string $url The definition URL
     * @return array<string, mixed>|null The definition or null if not found
     */
    public function getDefinition(string $url): ?array;

    /**
     * Get all FHIR definitions
     *
     * @return array<string, array<string, mixed>>
     */
    public function getDefinitions(): array;

    /**
     * Get the element namespace for a FHIR version
     *
     * @param string $version The FHIR version
     * @return PhpNamespace The element namespace
     */
    public function getElementNamespace(string $version): PhpNamespace;

    /**
     * Get the enum namespace for a FHIR version
     *
     * @param string $version The FHIR version
     * @return PhpNamespace The enum namespace
     */
    public function getEnumNamespace(string $version): PhpNamespace;

    /**
     * Add an element namespace for a FHIR version
     *
     * @param string $version The FHIR version
     * @param PhpNamespace $namespace The element namespace
     */
    public function addElementNamespace(string $version, PhpNamespace $namespace): void;

    /**
     * Add an enum namespace for a FHIR version
     *
     * @param string $version The FHIR version
     * @param PhpNamespace $namespace The enum namespace
     */
    public function addEnumNamespace(string $version, PhpNamespace $namespace): void;
}