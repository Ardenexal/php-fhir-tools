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
 *
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
     * Namespaces for primitive classes organized by FHIR version
     *
     * @var array<string, PhpNamespace>
     */
    private array $primitiveNamespaces = [];

    /**
     * Namespaces for datatype classes organized by FHIR version
     *
     * @var array<string, PhpNamespace>
     */
    private array $datatypeNamespaces = [];

    /**
     * Raw FHIR definitions loaded from packages, keyed by URL
     *
     * @var array<string, array<string, mixed>>
     */
    private array $definitions = [];

    /**
     * Generated FHIR resource classes, keyed by URL
     *
     * @var array<string, GeneratedClassInfo>
     */
    private array $resources = [];

    /**
     * Generated FHIR type classes, keyed by URL
     *
     * @var array<string, GeneratedClassInfo>
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
     * @var array<string, GeneratedClassInfo>
     */
    private array $enums = [];

    /**
     * @var array<string, class-string> - Pending enums to be generated (keyed by valueset/codesystem URL)
     */
    private array $pendingEnums = [];

    public function addResource(string $fhirUrl, string $namespace, ClassType $resource): void
    {
        $this->resources[$fhirUrl] = new GeneratedClassInfo($resource, $namespace, $fhirUrl);
    }

    public function getResources(): array
    {
        return $this->resources;
    }

    public function getResource(string $fhirUrl): ?GeneratedClassInfo
    {
        return $this->resources[$fhirUrl] ?? null;
    }

    public function addType(string $fhirUrl, string $namespace, ClassType $type): void
    {
        $this->types[$fhirUrl] = new GeneratedClassInfo($type, $namespace, $fhirUrl);
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function getType(string $path): ?GeneratedClassInfo
    {
        return array_find($this->types, fn (GeneratedClassInfo $info) => $info->fhirUrl === $path) ?? null;
    }

    public function addEnum(string $fhirUrl, string $namespace, EnumType $enum): void
    {
        $this->enums[$fhirUrl] = new GeneratedClassInfo($enum, $namespace, $fhirUrl);
    }

    public function getEnums(): array
    {
        return $this->enums;
    }

    public function getEnum(string $url): ?GeneratedClassInfo
    {
        return $this->enums[$url] ?? null;
    }

    public function addDefinition(string $url, array $definition): void
    {
        $this->definitions[$url] = $definition;
    }

    /**
     * @param array<string, mixed> $definition
     *
     * @return void
     */
    public function loadDefinitions(array $definition): void
    {
        $this->definitions = array_merge($this->definitions, $definition);
        $this->definitions = $this->sortDefinitionsByInheritance($this->definitions);
    }

    /**
     * Sort StructureDefinitions by inheritance and type dependencies using topological sort
     *
     * Ensures that:
     * 1. Base definitions appear before derived definitions (inheritance via baseDefinition)
     * 2. Referenced types appear before types that use them (type dependencies via element.type.code)
     *
     * This allows parent classes and dependent types to be generated before child classes
     * during code generation, preventing "class not found" and "type not found" errors.
     *
     * Algorithm:
     * 1. Separate StructureDefinitions from other resources
     * 2. Build multi-source dependency graph (inheritance + type references)
     * 3. Perform topological sort using DFS
     * 4. Merge sorted StructureDefinitions back with other resources
     *
     * @param array<string, array<string, mixed>> $definitions
     *
     * @return array<string, array<string, mixed>>
     */
    private function sortDefinitionsByInheritance(array $definitions): array
    {
        // Separate StructureDefinitions from other resource types
        $structureDefinitions = [];
        $otherResources       = [];

        foreach ($definitions as $url => $definition) {
            if (isset($definition['resourceType']) && $definition['resourceType'] === 'StructureDefinition') {
                $structureDefinitions[$url] = $definition;
            } else {
                $otherResources[$url] = $definition;
            }
        }

        // If no StructureDefinitions to sort, return as-is
        if (empty($structureDefinitions)) {
            return $definitions;
        }

        // Build dependency graph with both inheritance and type dependencies
        $dependencies = [];
        foreach ($structureDefinitions as $url => $definition) {
            $deps = [];

            // Add inheritance dependency (baseDefinition)
            if (isset($definition['baseDefinition'])) {
                $deps[] = $definition['baseDefinition'];
            }

            // Add type dependencies from elements
            $typeDeps = $this->extractTypeDependencies($definition, $structureDefinitions);
            $deps     = array_merge($deps, $typeDeps);

            if (! empty($deps)) {
                $dependencies[$url] = array_unique($deps);
            }
        }

        // Perform topological sort using DFS
        $sorted   = [];
        $visited  = [];
        $visiting = []; // Track nodes currently being visited to detect cycles

        $visit = function(string $url) use (
            &$visit,
            &$sorted,
            &$visited,
            &$visiting,
            $structureDefinitions,
            $dependencies
        ) {
            // If already visited, skip
            if (isset($visited[$url])) {
                return;
            }

            // Detect circular dependency
            if (isset($visiting[$url])) {
                // Circular dependency detected - just mark as visited and continue
                $visited[$url] = true;

                return;
            }

            // Mark as currently visiting
            $visiting[$url] = true;

            // Visit all dependencies (both inheritance and type deps) if they exist in our definition set
            if (isset($dependencies[$url])) {
                foreach ($dependencies[$url] as $depUrl) {
                    if (isset($structureDefinitions[$depUrl])) {
                        $visit($depUrl);
                    }
                    // If dependency doesn't exist in our set, it's likely a FHIR base type
                    // or external reference which will be handled separately or already exists
                }
            }

            // Mark as visited and add to sorted list
            unset($visiting[$url]);
            $visited[$url] = true;
            $sorted[$url]  = $structureDefinitions[$url];
        };

        // Visit all StructureDefinitions
        foreach (array_keys($structureDefinitions) as $url) {
            $visit($url);
        }

        // Merge sorted StructureDefinitions with other resources
        // Other resources maintain their original order
        return array_merge($sorted, $otherResources);
    }

    /**
     * Extract type dependencies from a StructureDefinition's elements
     *
     * Scans all elements in the snapshot to find type references that create
     * dependencies on other StructureDefinitions. Only includes types that
     * actually exist in the current definition set.
     *
     * @param array<string, mixed>                $definition           The StructureDefinition to analyze
     * @param array<string, array<string, mixed>> $structureDefinitions All available StructureDefinitions
     *
     * @return array<string> Array of URLs for dependent types
     */
    private function extractTypeDependencies(array $definition, array $structureDefinitions): array
    {
        $typeDeps = [];

        // Only process if snapshot with elements exists
        if (! isset($definition['snapshot']['element']) || ! is_array($definition['snapshot']['element'])) {
            return $typeDeps;
        }

        foreach ($definition['snapshot']['element'] as $element) {
            if (! isset($element['type']) || ! is_array($element['type'])) {
                continue;
            }

            foreach ($element['type'] as $type) {
                $code = $type['code'] ?? null;
                if ($code === null) {
                    continue;
                }

                // Normalize type code to URL and check if it's a dependency
                $typeUrl = $this->normalizeTypeCodeToUrl($code);
                if ($typeUrl !== null && isset($structureDefinitions[$typeUrl])) {
                    $typeDeps[] = $typeUrl;
                }
            }
        }

        return array_unique($typeDeps);
    }

    /**
     * Normalize a type code to a canonical StructureDefinition URL
     *
     * Handles various type code formats:
     * - Simple names: "HumanName" → "http://hl7.org/fhir/StructureDefinition/HumanName"
     * - Already URLs: kept as-is
     * - System types: "http://hl7.org/fhirpath/System.String" → null (not a dependency)
     *
     * Note: This method does not filter primitives or check existence - that's handled
     * by the caller (extractTypeDependencies) which only includes types that exist
     * in the current $structureDefinitions array.
     *
     * @param string $code The type code to normalize
     *
     * @return string|null The canonical URL or null if it's a system type
     */
    private function normalizeTypeCodeToUrl(string $code): ?string
    {
        // Skip FHIRPath System types - these are not StructureDefinitions
        if (str_starts_with($code, 'http://hl7.org/fhirpath/System.')) {
            return null;
        }

        // If already a full URL, return as-is
        if (str_starts_with($code, 'http://') || str_starts_with($code, 'https://')) {
            return $code;
        }

        // Convert simple name to canonical FHIR URL
        // Primitives and external types will be filtered out by the caller
        // since they won't exist in the $structureDefinitions array
        return 'http://hl7.org/fhir/StructureDefinition/' . $code;
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

    public function getPrimitiveNamespace(string $version): PhpNamespace
    {
        if (!isset($this->primitiveNamespaces[$version])) {
            throw GenerationException::missingNamespace($version, 'primitive');
        }

        return $this->primitiveNamespaces[$version];
    }

    public function addPrimitiveNamespace(string $version, PhpNamespace $namespace): void
    {
        $this->primitiveNamespaces[$version] = $namespace;
    }

    public function getDatatypeNamespace(string $version): PhpNamespace
    {
        if (!isset($this->datatypeNamespaces[$version])) {
            throw GenerationException::missingNamespace($version, 'datatype');
        }

        return $this->datatypeNamespaces[$version];
    }

    public function addDatatypeNamespace(string $version, PhpNamespace $namespace): void
    {
        $this->datatypeNamespaces[$version] = $namespace;
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
