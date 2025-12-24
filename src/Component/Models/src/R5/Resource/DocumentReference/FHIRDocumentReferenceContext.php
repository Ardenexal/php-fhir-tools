<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;

/**
 * @description The clinical context in which the document was prepared.
 */
#[FHIRBackboneElement(parentResource: 'DocumentReference', elementPath: 'DocumentReference.context', fhirVersion: 'R4B')]
class FHIRDocumentReferenceContext extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRReference> encounter Context of the document  content */
        public array $encounter = [],
        /** @var array<FHIRCodeableConcept> event Main clinical acts documented */
        public array $event = [],
        /** @var FHIRPeriod|null period Time of service that is being documented */
        public ?FHIRPeriod $period = null,
        /** @var FHIRCodeableConcept|null facilityType Kind of facility where patient was seen */
        public ?FHIRCodeableConcept $facilityType = null,
        /** @var FHIRCodeableConcept|null practiceSetting Additional details about where the content was created (e.g. clinical specialty) */
        public ?FHIRCodeableConcept $practiceSetting = null,
        /** @var FHIRReference|null sourcePatientInfo Patient demographics from source */
        public ?FHIRReference $sourcePatientInfo = null,
        /** @var array<FHIRReference> related Related identifiers or resources */
        public array $related = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
