<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;

/**
 * @author HL7 International / Health Care Devices
 *
 * @see http://hl7.org/fhir/StructureDefinition/device-alertDetection
 *
 * @description Alert detection activation state describes whether a device is set to annunciate when a [DeviceAlert](http://hl7.org/fhir/StructureDefinition/DeviceAlert) condition occurs. This extension describes the reported alert detection activation state for the indicated combination of alert code and priority at the indicated point in time. The extension may be used on a [Device](http://hl7.org/fhir/StructureDefinition/Device), or on the specific [DeviceMetric](http://hl7.org/fhir/StructureDefinition/DeviceMetric) that could detect the condition or annunciate the alert.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/device-alertDetection', fhirVersion: 'R4')]
class DeviceAlertDetectionExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodePrimitive activationState The activation state of the specified alert (or alerts) */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $activationState,
        /** @var CodeableConcept|null alertCode The alert for which the alert detection activation state is described */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $alertCode = null,
        /** @var CodePrimitive|null priority The alert priority for which the alert detection activation state is described */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $priority = null,
        /** @var DateTimePrimitive|null effective The point(s) in time this activation state was in effect */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $effective = null,
        /** @var Range|null limitRange Limits applicable to the indicated alert priority. */
        #[FhirProperty(fhirType: 'Range', propertyKind: 'complex')]
        public ?Range $limitRange = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'activationState', value: $this->activationState);
        if ($this->alertCode !== null) {
            $subExtensions[] = new Extension(url: 'alertCode', value: $this->alertCode);
        }
        if ($this->priority !== null) {
            $subExtensions[] = new Extension(url: 'priority', value: $this->priority);
        }
        if ($this->effective !== null) {
            $subExtensions[] = new Extension(url: 'effective', value: $this->effective);
        }
        if ($this->limitRange !== null) {
            $subExtensions[] = new Extension(url: 'limitRange', value: $this->limitRange);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/device-alertDetection',
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
        $alertCode       = null;
        $priority        = null;
        $effective       = null;
        $activationState = null;
        $limitRange      = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'alertCode' && $ext->value instanceof CodeableConcept) {
                $alertCode = $ext->value;
            }
            if ($extUrl === 'priority' && $ext->value instanceof CodePrimitive) {
                $priority = $ext->value;
            }
            if ($extUrl === 'effective' && $ext->value instanceof DateTimePrimitive) {
                $effective = $ext->value;
            }
            if ($extUrl === 'activationState' && $ext->value instanceof CodePrimitive) {
                $activationState = $ext->value;
            }
            if ($extUrl === 'limitRange' && $ext->value instanceof Range) {
                $limitRange = $ext->value;
            }
        }

        if ($activationState === null) {
            throw new \InvalidArgumentException('Required sub-extension "activationState" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($alertCode, $priority, $effective, $activationState, $limitRange, $id);
    }
}
