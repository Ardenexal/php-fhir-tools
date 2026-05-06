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
 * @see http://hl7.org/fhir/StructureDefinition/DiagnosticReport-geneticsAnalysis
 *
 * @description Knowledge-based comments on the effect of the sequence on patient's condition/medication reaction.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/DiagnosticReport-geneticsAnalysis', fhirVersion: 'R4B')]
class AnalysisExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept type Analysis type */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $type,
        /** @var CodeableConcept|null interpretation Analysis interpretation */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $interpretation = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'type', value: $this->type);
        if ($this->interpretation !== null) {
            $subExtensions[] = new Extension(url: 'interpretation', value: $this->interpretation);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/DiagnosticReport-geneticsAnalysis',
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
        $interpretation = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
                $type = $ext->value;
            }
            if ($extUrl === 'interpretation' && $ext->value instanceof CodeableConcept) {
                $interpretation = $ext->value;
            }
        }

        if ($type === null) {
            throw new \InvalidArgumentException('Required sub-extension "type" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($type, $interpretation, $id);
    }
}
