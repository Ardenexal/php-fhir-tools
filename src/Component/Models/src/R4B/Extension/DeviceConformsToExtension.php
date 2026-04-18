<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\RelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/device-conformsTo
 *
 * @description Identifies the standards, specifications, or formal guidances for the capabilities supported by the device. The device may be certified as conformant to these specifications e.g., communication, performance, process, measurement, or specialization standards.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/device-conformsTo', fhirVersion: 'R4B')]
class DeviceConformsToExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept specification Identifies the standard, specification, or formal guidance that the device adheres        to the Device Specification type */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $specification,
        /** @var CodeableConcept|null category Describes the common type of the standard, specification, or formal guidance */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $category = null,
        /** @var array<StringPrimitive> version The specific form or variant of the standard, specification or formal guidance */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $version = [],
        /** @var array<RelatedArtifact> source Standard, regulation, certification, or guidance website, document, or other publication,        or similar, supporting the conformance */
        #[FhirProperty(fhirType: 'RelatedArtifact', propertyKind: 'complex', isArray: true)]
        public array $source = [],
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'specification', value: $this->specification);
        if ($this->category !== null) {
            $subExtensions[] = new Extension(url: 'category', value: $this->category);
        }
        foreach ($this->version as $v) {
            $subExtensions[] = new Extension(url: 'version', value: $v);
        }
        foreach ($this->source as $v) {
            $subExtensions[] = new Extension(url: 'source', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/device-conformsTo',
        );
    }

    /**
     * Reconstruct from an array of already-denormalized sub-extension objects.
     *
     * @param array<FHIRExtensionInterface> $subExtensions
     * @param string|null                   $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
    {
        $category      = null;
        $specification = null;
        $version       = [];
        $source        = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'category' && $ext->value instanceof CodeableConcept) {
                $category = $ext->value;
            }
            if ($extUrl === 'specification' && $ext->value instanceof CodeableConcept) {
                $specification = $ext->value;
            }
            if ($extUrl === 'version' && $ext->value instanceof StringPrimitive) {
                $version[] = $ext->value;
            }
            if ($extUrl === 'source' && $ext->value instanceof RelatedArtifact) {
                $source[] = $ext->value;
            }
        }

        if ($specification === null) {
            throw new \InvalidArgumentException('Required sub-extension "specification" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($category, $specification, $version, $source, $id);
    }
}
