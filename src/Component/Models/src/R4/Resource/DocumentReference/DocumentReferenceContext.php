<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DocumentReference;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @description The clinical context in which the document was prepared.
 */
#[FHIRBackboneElement(parentResource: 'DocumentReference', elementPath: 'DocumentReference.context', fhirVersion: 'R4')]
class DocumentReferenceContext extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<Reference> encounter Context of the document  content */
        public array $encounter = [],
        /** @var array<CodeableConcept> event Main clinical acts documented */
        public array $event = [],
        /** @var Period|null period Time of service that is being documented */
        public ?Period $period = null,
        /** @var CodeableConcept|null facilityType Kind of facility where patient was seen */
        public ?CodeableConcept $facilityType = null,
        /** @var CodeableConcept|null practiceSetting Additional details about where the content was created (e.g. clinical specialty) */
        public ?CodeableConcept $practiceSetting = null,
        /** @var Reference|null sourcePatientInfo Patient demographics from source */
        public ?Reference $sourcePatientInfo = null,
        /** @var array<Reference> related Related identifiers or resources */
        public array $related = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
