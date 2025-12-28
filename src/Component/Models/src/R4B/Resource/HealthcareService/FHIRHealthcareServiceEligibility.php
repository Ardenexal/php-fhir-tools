<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;

/**
 * @description Does this service have specific eligibility requirements that need to be met in order to use the service?
 */
#[FHIRBackboneElement(parentResource: 'HealthcareService', elementPath: 'HealthcareService.eligibility', fhirVersion: 'R4B')]
class FHIRHealthcareServiceEligibility extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Coded value for the eligibility */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRMarkdown|null comment Describes the eligibility conditions for the service */
        public ?FHIRMarkdown $comment = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
