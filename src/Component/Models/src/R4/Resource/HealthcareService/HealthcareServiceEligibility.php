<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\HealthcareService;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;

/**
 * @description Does this service have specific eligibility requirements that need to be met in order to use the service?
 */
#[FHIRBackboneElement(parentResource: 'HealthcareService', elementPath: 'HealthcareService.eligibility', fhirVersion: 'R4')]
class HealthcareServiceEligibility extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code Coded value for the eligibility */
        public ?CodeableConcept $code = null,
        /** @var MarkdownPrimitive|null comment Describes the eligibility conditions for the service */
        public ?MarkdownPrimitive $comment = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
