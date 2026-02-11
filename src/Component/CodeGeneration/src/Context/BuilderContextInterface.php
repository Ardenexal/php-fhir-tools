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
 *
 * @since 1.0.0
 */
interface BuilderContextInterface
{
    /**
     * Add a generated resource class
     *
     * @param string    $fhirUrl   The FHIR resource URL
     * @param string    $namespace The namespace the class is assigned to
     * @param ClassType $resource  The generated resource class
     */
    public function addResource(string $fhirUrl, string $namespace, ClassType $resource): void;

    /**
     * Get all generated resource classes
     *
     * @return array<string, GeneratedClassInfo>
     */
    public function getResources(): array;

    /**
     * Get a specific resource by URL
     *
     * @param string $fhirUrl The FHIR resource URL
     *
     * @return GeneratedClassInfo|null The resource info or null if not found
     */
    public function getResource(string $fhirUrl): ?GeneratedClassInfo;

    /**
     * Add a generated type class
     *
     * @param string    $fhirUrl   The FHIR type URL or path
     * @param string    $namespace The namespace the class is assigned to
     * @param ClassType $type      The generated type class
     */
    public function addType(string $fhirUrl, string $namespace, ClassType $type): void;

    /**
     * Get all generated type classes
     *
     * @return array<string, GeneratedClassInfo>
     */
    public function getTypes(): array;

    /**
     * Get a specific type by URL or path
     *
     * @param string $path The type path or URL
     *
     * @return GeneratedClassInfo|null The type info or null if not found
     */
    public function getType(string $path): ?GeneratedClassInfo;

    /**
     * Add a generated enum
     *
     * @param string   $fhirUrl   The enum URL
     * @param string   $namespace The namespace the enum is assigned to
     * @param EnumType $enum      The generated enum
     */
    public function addEnum(string $fhirUrl, string $namespace, EnumType $enum): void;

    /**
     * Get all generated enums
     *
     * @return array<string, GeneratedClassInfo>
     */
    public function getEnums(): array;

    /**
     * Get a specific enum by URL
     *
     * @param string $url The enum URL
     *
     * @return GeneratedClassInfo|null The enum info or null if not found
     */
    public function getEnum(string $url): ?GeneratedClassInfo;

    /**
     * Add a FHIR definition
     *
     * @param string               $url        The definition URL
     * @param array<string, mixed> $definition The FHIR definition
     */
    public function addDefinition(string $url, array $definition): void;

    /**
     * Get a FHIR definition by URL
     *
     * @param string $url The definition URL
     *
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
     *
     * @return PhpNamespace The element namespace
     */
    public function getElementNamespace(string $version): PhpNamespace;

    /**
     * Get the enum namespace for a FHIR version
     *
     * @param string $version The FHIR version
     *
     * @return PhpNamespace The enum namespace
     */
    public function getEnumNamespace(string $version): PhpNamespace;

    /**
     * Add an element namespace for a FHIR version
     *
     * @param string       $version   The FHIR version
     * @param PhpNamespace $namespace The element namespace
     */
    public function addElementNamespace(string $version, PhpNamespace $namespace): void;

    /**
     * Add an enum namespace for a FHIR version
     *
     * @param string       $version   The FHIR version
     * @param PhpNamespace $namespace The enum namespace
     */
    public function addEnumNamespace(string $version, PhpNamespace $namespace): void;

    /**
     * Get the primitive namespace for a FHIR version
     *
     * @param string $version The FHIR version
     *
     * @return PhpNamespace The primitive namespace
     */
    public function getPrimitiveNamespace(string $version): PhpNamespace;

    /**
     * Add a primitive namespace for a FHIR version
     *
     * @param string       $version   The FHIR version
     * @param PhpNamespace $namespace The primitive namespace
     */
    public function addPrimitiveNamespace(string $version, PhpNamespace $namespace): void;

    /**
     * Get the datatype namespace for a FHIR version
     *
     * @param string $version The FHIR version
     *
     * @return PhpNamespace The datatype namespace
     */
    public function getDatatypeNamespace(string $version): PhpNamespace;

    /**
     * Add a datatype namespace for a FHIR version
     *
     * @param string       $version   The FHIR version
     * @param PhpNamespace $namespace The datatype namespace
     */
    public function addDatatypeNamespace(string $version, PhpNamespace $namespace): void;

    /**
     * Add a pending enum for generation
     *
     * Tracks ValueSet URLs that require enum generation during the selective
     * generation process. These enums will be generated only if they are
     * referenced by StructureDefinitions with required binding strength.
     *
     * @param string       $url      The ValueSet URL
     * @param class-string $enumName The enum class name to generate
     */
    public function addPendingEnum(string $url, string $enumName): void;

    /**
     * Get all pending enums awaiting generation
     *
     * Returns a map of ValueSet URLs to their corresponding enum class names
     * that have been marked for generation during dependency tracking.
     *
     * @return array<string, class-string> Map of ValueSet URL to enum class name
     */
    public function getPendingEnums(): array;

    /**
     * Remove a pending enum after generation
     *
     * Removes a ValueSet URL from the pending enums list, typically called
     * after the enum has been successfully generated.
     *
     * @param string $valuesetURL The ValueSet URL to remove
     */
    public function removePendingEnum(string $valuesetURL): void;

    /**
     * Add a pending type for generation
     *
     * Tracks type URLs that require class generation. This is used for
     * managing dependencies between types during the generation process.
     *
     * @param string       $url           The type URL
     * @param class-string $typeClassName The type class name to generate
     */
    public function addPendingType(string $url, string $typeClassName): void;

    /**
     * Get all pending types awaiting generation
     *
     * Returns a map of type URLs to their corresponding class names
     * that have been marked for generation.
     *
     * @return array<string, class-string> Map of type URL to class name
     */
    public function getPendingTypes(): array;

    /**
     * Check if a type is pending generation
     *
     * Determines whether a specific type URL has been marked for generation
     * but has not yet been processed.
     *
     * @param string $url The type URL to check
     *
     * @return bool True if the type is pending generation, false otherwise
     */
    public function hasPendingType(string $url): bool;

    /**
     * Remove a pending type after generation
     *
     * Removes a type URL from the pending types list, typically called
     * after the type has been successfully generated.
     *
     * @param string $url The type URL to remove
     */
    public function removePendingType(string $url): void;
}
