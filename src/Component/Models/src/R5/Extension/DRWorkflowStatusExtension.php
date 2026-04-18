<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\InstantPrimitive;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/diagnosticReport-workflowStatus
 *
 * @description The workflow status of the diagnostic report.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/diagnosticReport-workflowStatus', fhirVersion: 'R5')]
class DRWorkflowStatusExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept valueSlice Meaning of the status */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $valueSlice,
        /** @var InstantPrimitive timestamp Status timestamp */
        #[FhirProperty(fhirType: 'instant', propertyKind: 'primitive')]
        public InstantPrimitive $timestamp,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'value', value: $this->valueSlice);
        $subExtensions[] = new Extension(url: 'timestamp', value: $this->timestamp);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/diagnosticReport-workflowStatus',
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
        $valueSlice = null;
        $timestamp  = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'value' && $ext->value instanceof CodeableConcept) {
                $valueSlice = $ext->value;
            }
            if ($extUrl === 'timestamp' && $ext->value instanceof InstantPrimitive) {
                $timestamp = $ext->value;
            }
        }

        if ($valueSlice === null) {
            throw new \InvalidArgumentException('Required sub-extension "value" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($timestamp === null) {
            throw new \InvalidArgumentException('Required sub-extension "timestamp" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($valueSlice, $timestamp, $id);
    }
}
