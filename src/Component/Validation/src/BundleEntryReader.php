<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;

/**
 * Reads the values the Bundle and contained-reference checkers need from a model, and indexes a
 * Bundle's entries.
 *
 * Generated models are read through their public properties, and a deserialized model bypasses the
 * constructor, so an absent field is uninitialized rather than null. Every read here answers null for
 * an absent, uninitialized or non-public property instead of throwing.
 *
 * @internal shared by the Bundle checkers and {@see ContainedReferenceChecker}, not part of the public API
 */
final class BundleEntryReader
{
    /** @var array<class-string, string|null> FHIR resource type per class; reflection is not free */
    private array $resourceTypes = [];

    /** @var array<class-string, string|null> FHIR type of a complex type or primitive, per class */
    private array $typeNames = [];

    /** @var array<class-string, bool> whether a class models a FHIR primitive */
    private array $primitives = [];

    public function __construct(
        private readonly FHIRModelAccessorInterface $models,
        private readonly FHIRAttributeReaderInterface $attributes,
    ) {
    }

    /** Read every entry of a Bundle once, by its position in `Bundle.entry`. */
    public function index(object $bundle): BundleEntryIndex
    {
        $value   = $this->readPublic($bundle, 'entry');
        $entries = [];

        foreach (is_array($value) ? $value : [] as $index => $entry) {
            if (!is_int($index) || !is_object($entry)) {
                continue;
            }

            $resource = $this->readObject($entry, 'resource');
            $meta     = $resource === null ? null : $this->readObject($resource, 'meta');

            $entries[$index] = [
                'entry'      => $entry,
                'fullUrl'    => $this->readString($entry, 'fullUrl'),
                // `"fullUrl": ""` is present but empty, which is a different finding from a missing one.
                'hasFullUrl' => $this->readPublic($entry, 'fullUrl') !== null,
                'resource'   => $resource,
                'type'       => $resource === null ? null : $this->resourceType($resource),
                'id'         => $resource === null ? null : $this->readString($resource, 'id'),
                'version'    => $meta     === null ? null : $this->readString($meta, 'versionId'),
            ];
        }

        return new BundleEntryIndex($entries);
    }

    /**
     * Read a string-ish property: a primitive wrapper, a backed enum, a bare string, or absent.
     *
     * An empty string reads as null, so a caller that must tell `""` from absent uses {@see readPublic()}.
     */
    public function readString(object $node, string $property): ?string
    {
        $value = $this->readPublic($node, $property);

        if ($value instanceof \BackedEnum) {
            $value = $value->value;
        }

        if (is_string($value)) {
            return $value === '' ? null : $value;
        }

        if ($value instanceof \Stringable) {
            $string = (string) $value;

            return $string === '' ? null : $string;
        }

        return null;
    }

    public function readObject(object $node, string $property): ?object
    {
        $value = $this->readPublic($node, $property);

        return is_object($value) ? $value : null;
    }

    /** A public property's value, or null when it is absent, non-public or never written. */
    public function readPublic(object $node, string $property): mixed
    {
        if (!in_array($property, $this->models->publicPropertyNames($node), true)) {
            return null;
        }

        if (!$this->models->isPropertyInitialized($node, $property)) {
            return null;
        }

        return $this->models->readInitializedValue($node, $property);
    }

    /**
     * Every initialized public property of a node, for walking the tree beneath it.
     *
     * @return array<string, mixed>
     */
    public function children(object $node): array
    {
        return $this->models->initializedPublicValues($node);
    }

    /** The FHIR resource type a class stands for, or null when it is not a resource. */
    public function resourceType(object $node): ?string
    {
        $key = $node::class;

        if (array_key_exists($key, $this->resourceTypes)) {
            return $this->resourceTypes[$key];
        }

        $attributes = $this->attributes->classAttributes($node, FhirResource::class);

        return $this->resourceTypes[$key] = $attributes === [] ? null : $attributes[0]->getResourceType();
    }

    /** The FHIR type a complex-type or primitive class stands for, such as `Reference` or `uri`. */
    public function typeName(object $node): ?string
    {
        $key = $node::class;

        if (array_key_exists($key, $this->typeNames)) {
            return $this->typeNames[$key];
        }

        $type    = null;
        $complex = $this->attributes->classAttributes($node, FHIRComplexType::class);

        if ($complex !== []) {
            $type = $complex[0]->typeName;
        } else {
            $primitive = $this->attributes->classAttributes($node, FHIRPrimitive::class);

            if ($primitive !== []) {
                $type = $primitive[0]->primitiveType;
            }
        }

        return $this->typeNames[$key] = $type;
    }

    /** Whether a class models a FHIR primitive, which holds only a value, an id and extensions. */
    public function isPrimitive(object $node): bool
    {
        return $this->primitives[$node::class] ??= $this->attributes->classAttributes($node, FHIRPrimitive::class) !== [];
    }
}
