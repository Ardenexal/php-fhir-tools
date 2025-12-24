<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;

/**
 * @description The clinical context in which the document was prepared.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'DocumentReference', elementPath: 'DocumentReference.context', fhirVersion: 'R4')]
class FHIRDocumentReferenceContext extends FHIRBackboneElement
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
