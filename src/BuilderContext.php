<?php

namespace Ardenexal\FHIRTools;

use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\PhpNamespace;

class BuilderContext
{
    public const DEFAULT_CLASS_PREFIX = 'FHIR';

    /**
     * @var array<string, PhpNamespace> - Namespace per FHIR version Element
     */
    private array $elementNamespaces = [];

    /**
     * @var array<string, PhpNamespace> - Namespace per FHIR version Enum
     */
    private array $enumNamespaces = [];

    /**
     * @var array<string, mixed> - Raw definitions from the FHIR spec (keyed by URL)
     */
    private array $definitions = [];

    /**
     * @var array<string, ClassType>
     */
    private array $resources = [];

    /**
     * @var array <string, ClassType>
     */
    private array $types = [];

    /**
     * @var array<string, class-string> - Pending types to be generated (keyed by URL)
     */
    private array $pendingTypes = [];

    /**
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

    /**
     * @return ClassType[]
     */
    public function getResources(): array
    {
        return $this->resources;
    }

    /**
     * @param string $url
     *
     * @return ClassLike|null
     */
    public function has(string $url): ?ClassLike
    {
        return $this->resources[$url] ?? null;
    }

    public function addType(string $url, ClassType $type): void
    {
        $this->types[$url] = $type;
    }

    /**
     * @return ClassType[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    public function addEnum(string $url, EnumType $enum): void
    {
        $this->enums[$url] = $enum;
    }

    /**
     * @return EnumType[]
     */
    public function getEnums(): array
    {
        return $this->enums;
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
     * @param string  $url
     * @param mixed[] $definition
     *
     * @return void
     */
    public function addDefinition(string $url, array $definition): void
    {
        $this->definitions[$url] = $definition;
    }

    /**
     * @param string $url
     *
     * @return mixed|null
     */
    public function getDefinition(string $url): mixed
    {
        return $this->definitions[$url] ?? null;
    }

    /**
     * @param string $url
     *
     * @return EnumType|null
     */
    public function getEnum(string $url): ?EnumType
    {
        return $this->enums[$url] ?? null;
    }

    public function addElementNamespace(string $version, PhpNamespace $namespace): void
    {
        $this->elementNamespaces[$version] = $namespace;
    }

    public function addEnumNamespace(string $version, PhpNamespace $namespace): void
    {
        $this->enumNamespaces[$version] = $namespace;
    }

    public function getElementNamespace(string $version): PhpNamespace
    {
        if (!isset($this->elementNamespaces[$version])) {
            throw new \RuntimeException("No element namespace found for FHIR version $version");
        }

        return $this->elementNamespaces[$version];
    }

    public function getEnumNamespace(string $version): PhpNamespace
    {
        if (!isset($this->enumNamespaces[$version])) {
            throw new \RuntimeException("No Enum namespace found for FHIR version $version");
        }

        return $this->enumNamespaces[$version];
    }
}
