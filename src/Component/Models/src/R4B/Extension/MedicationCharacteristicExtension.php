<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / Pharmacy
 *
 * @see http://hl7.org/fhir/StructureDefinition/medication-characteristic
 *
 * @description Provides additional characteristics of a medication, such as physical properties,
 * storage requirements, or other distinguishing attributes. This extension mirrors
 * the pattern of characteristic backbone elements found in other definitional
 * resources, allowing a Medication resource to carry typed key-value pairs that
 * describe observable or defining haracteristics of the medication.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/medication-characteristic', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'Medication')]
class MedicationCharacteristicExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept type The characteristic (e.g., colour, shape, storage temperature) */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $type,
        /** @var bool valueSlice The value of the characteristic */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public bool $valueSlice,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'type', value: $this->type);
        $subExtensions[] = new Extension(url: 'value', value: $this->valueSlice);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/medication-characteristic',
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
        $type       = null;
        $valueSlice = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
                $type = $ext->value;
            }
            if ($extUrl === 'value' && is_bool($ext->value)) {
                $valueSlice = $ext->value;
            }
        }

        if ($type === null) {
            throw new \InvalidArgumentException('Required sub-extension "type" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($valueSlice === null) {
            throw new \InvalidArgumentException('Required sub-extension "value" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($type, $valueSlice, $id);
    }
}
