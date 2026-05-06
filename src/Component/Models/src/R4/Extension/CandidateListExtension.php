<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/task-candidateList
 *
 * @description Identifies the individuals who are candidates for being the owner of the task. The list of candidates that perform a task is now achieved by using Task.requestedPerformer and this element can be implemented using the cross version extension mechanism (i.e., the extension is no longer needed).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/task-candidateList', fhirVersion: 'R4')]
class CandidateListExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/task-candidateList',
            value: $this->valueReference,
        );
    }
}
