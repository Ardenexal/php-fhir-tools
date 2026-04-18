<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/observation-geneticsAncestry
 *
 * @description Ancestry information.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsAncestry', fhirVersion: 'R4B')]
class AncestryExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept name Ancestry name */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $name,
        /** @var string|null percentage Ancestry percentage */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $percentage = null,
        /** @var CodeableConcept|null source Source of ancestry report */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $source = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'Name', value: $this->name);
        if ($this->percentage !== null) {
            $subExtensions[] = new Extension(url: 'Percentage', value: $this->percentage);
        }
        if ($this->source !== null) {
            $subExtensions[] = new Extension(url: 'Source', value: $this->source);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsAncestry',
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
        $name       = null;
        $percentage = null;
        $source     = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'Name' && $ext->value instanceof CodeableConcept) {
                $name = $ext->value;
            }
            if ($extUrl === 'Percentage' && is_string($ext->value)) {
                $percentage = $ext->value;
            }
            if ($extUrl === 'Source' && $ext->value instanceof CodeableConcept) {
                $source = $ext->value;
            }
        }

        if ($name === null) {
            throw new \InvalidArgumentException('Required sub-extension "Name" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($name, $percentage, $source, $id);
    }
}
