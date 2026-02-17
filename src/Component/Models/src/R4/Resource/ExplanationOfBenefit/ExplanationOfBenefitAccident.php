<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;

/**
 * @description Details of a accident which resulted in injuries which required the products and services listed in the claim.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.accident', fhirVersion: 'R4')]
class ExplanationOfBenefitAccident extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var DatePrimitive|null date When the incident occurred */
        public ?DatePrimitive $date = null,
        /** @var CodeableConcept|null type The nature of the accident */
        public ?CodeableConcept $type = null,
        /** @var Address|Reference|null locationX Where the event occurred */
        public Address|Reference|null $locationX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
