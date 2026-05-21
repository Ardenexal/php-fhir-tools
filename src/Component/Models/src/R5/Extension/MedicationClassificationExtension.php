<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / Pharmacy
 *
 * @see http://hl7.org/fhir/StructureDefinition/medication-classification
 *
 * @description Provides a classification for a medication, mirroring the structure of
 * MedicationKnowledge.classification. This allows a Medication resource to carry
 * classification information (e.g., therapeutic class, pharmacologic class) in
 * , including the type of
 * classification system used, one or more classification codes, and an optional
 * source reference.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/medication-classification', fhirVersion: 'R5')]
class MedicationClassificationExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept type The kind of classification (e.g., anatomical, therapeutic, pharmacologic, regulatory) */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $type,
        /** @var array<CodeableConcept> classification The specific classification code within the scheme declared in type (e.g., an ATC code, a therapeutic-class code) */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
        public array $classification = [],
        /** @var StringPrimitive|null source The source or reference for the classification (as a string or URI) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $source = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'type', value: $this->type);
        foreach ($this->classification as $v) {
            $subExtensions[] = new Extension(url: 'classification', value: $v);
        }
        if ($this->source !== null) {
            $subExtensions[] = new Extension(url: 'source', value: $this->source);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/medication-classification',
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
        $type           = null;
        $classification = [];
        $source         = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
                $type = $ext->value;
            }
            if ($extUrl === 'classification' && $ext->value instanceof CodeableConcept) {
                $classification[] = $ext->value;
            }
            if ($extUrl === 'source' && $ext->value instanceof StringPrimitive) {
                $source = $ext->value;
            }
        }

        if ($type === null) {
            throw new \InvalidArgumentException('Required sub-extension "type" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($type, $classification, $source, $id);
    }
}
