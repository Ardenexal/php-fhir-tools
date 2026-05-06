<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/workflow-protectiveFactor
 *
 * @description Characteristics or strengths of individuals, families, community situations or societies that mitigate risks and promote positivewell-being and healthy development; attributes that help to successfully navigate difficult situations; factors that may contribute to or explain positive outcomes.  A trait or habit that "protects" people and makes them less likely to get a chronic disease that include, but are not limited to exercise, healthy eating, managing weight, managing blood pressure and cholesterol, managing mental health,  feeling happy, strong emotional support and social connections.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-protectiveFactor', fhirVersion: 'R4')]
class ProtectiveFactorExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive datatype DataType name 'CodeableReference' from R5 */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $datatype,
        /** @var CodeableConcept|null concept Reference to a concept (by class) */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $concept = null,
        /** @var Reference|null reference Reference to a resource (by instance) */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $reference = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: '_datatype', value: $this->datatype);
        if ($this->concept !== null) {
            $subExtensions[] = new Extension(url: 'concept', value: $this->concept);
        }
        if ($this->reference !== null) {
            $subExtensions[] = new Extension(url: 'reference', value: $this->reference);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/workflow-protectiveFactor',
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
        $datatype  = null;
        $concept   = null;
        $reference = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === '_datatype' && $ext->value instanceof StringPrimitive) {
                $datatype = $ext->value;
            }
            if ($extUrl === 'concept' && $ext->value instanceof CodeableConcept) {
                $concept = $ext->value;
            }
            if ($extUrl === 'reference' && $ext->value instanceof Reference) {
                $reference = $ext->value;
            }
        }

        if ($datatype === null) {
            throw new \InvalidArgumentException('Required sub-extension "_datatype" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($datatype, $concept, $reference, $id);
    }
}
