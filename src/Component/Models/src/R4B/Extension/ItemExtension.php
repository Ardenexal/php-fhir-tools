<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/servicerequest-geneticsItem
 *
 * @description The specific diagnostic investigations that are requested as part of this request. Sometimes, there can only be one item per request, but in most contexts, more than one investigation can be requested.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/servicerequest-geneticsItem', fhirVersion: 'R4B')]
class ItemExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept code Code to indicate the item (test, panel or sequence variant) being ordered */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $code,
        /** @var Reference|null geneticsObservation Indicate the genetic variant ordered to be tested */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $geneticsObservation = null,
        /** @var Reference|null specimen If this item relates to specific specimens */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $specimen = null,
        /** @var CodePrimitive|null status proposed | draft | planned | requested | received | accepted | in-progress | review | completed | cancelled | suspended | rejected | failed */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $status = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'code', value: $this->code);
        if ($this->geneticsObservation !== null) {
            $subExtensions[] = new Extension(url: 'geneticsObservation', value: $this->geneticsObservation);
        }
        if ($this->specimen !== null) {
            $subExtensions[] = new Extension(url: 'specimen', value: $this->specimen);
        }
        if ($this->status !== null) {
            $subExtensions[] = new Extension(url: 'status', value: $this->status);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/servicerequest-geneticsItem',
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
        $code                = null;
        $geneticsObservation = null;
        $specimen            = null;
        $status              = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'code' && $ext->value instanceof CodeableConcept) {
                $code = $ext->value;
            }
            if ($extUrl === 'geneticsObservation' && $ext->value instanceof Reference) {
                $geneticsObservation = $ext->value;
            }
            if ($extUrl === 'specimen' && $ext->value instanceof Reference) {
                $specimen = $ext->value;
            }
            if ($extUrl === 'status' && $ext->value instanceof CodePrimitive) {
                $status = $ext->value;
            }
        }

        if ($code === null) {
            throw new \InvalidArgumentException('Required sub-extension "code" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($code, $geneticsObservation, $specimen, $status, $id);
    }
}
