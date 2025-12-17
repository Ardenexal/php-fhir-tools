<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Metadata;

/**
 * Interface for extracting FHIR metadata from PHP objects using attributes
 * and other metadata sources to support serialization decisions.
 *
 * @author Kiro AI Assistant
 */
interface FHIRMetadataExtractorInterface
{
    /**
     * Extracts the FHIR resource type from a resource object.
     *
     * @param object $object The FHIR resource object to analyze
     *
     * @return string|null The resource type (e.g., "Patient", "Observation"), or null if not a resource
     */
    public function extractResourceType(object $object): ?string;

    /**
     * Extracts the FHIR type name from any FHIR object.
     *
     * @param object $object The FHIR object to analyze
     *
     * @return string|null The FHIR type name, or null if not determinable
     */
    public function extractFHIRType(object $object): ?string;

    /**
     * Determines if the given object is a FHIR resource.
     *
     * @param object $object The object to check
     *
     * @return bool True if the object is a FHIR resource, false otherwise
     */
    public function isResource(object $object): bool;

    /**
     * Determines if the given object is a FHIR complex type.
     *
     * @param object $object The object to check
     *
     * @return bool True if the object is a FHIR complex type, false otherwise
     */
    public function isComplexType(object $object): bool;

    /**
     * Determines if the given object is a FHIR primitive type.
     *
     * @param object $object The object to check
     *
     * @return bool True if the object is a FHIR primitive type, false otherwise
     */
    public function isPrimitiveType(object $object): bool;

    /**
     * Determines if the given object is a FHIR backbone element.
     *
     * @param object $object The object to check
     *
     * @return bool True if the object is a FHIR backbone element, false otherwise
     */
    public function isBackboneElement(object $object): bool;

    /**
     * Extracts the FHIR version from the object's metadata.
     *
     * @param object $object The FHIR object to analyze
     *
     * @return string|null The FHIR version (e.g., "R4B"), or null if not determinable
     */
    public function extractFHIRVersion(object $object): ?string;

    /**
     * Extracts the parent resource information for backbone elements.
     *
     * @param object $object The backbone element object to analyze
     *
     * @return string|null The parent resource type, or null if not a backbone element
     */
    public function extractParentResource(object $object): ?string;

    /**
     * Extracts the element path for backbone elements.
     *
     * @param object $object The backbone element object to analyze
     *
     * @return string|null The element path (e.g., "Patient.contact"), or null if not determinable
     */
    public function extractElementPath(object $object): ?string;
}
