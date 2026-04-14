<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR I WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/task-candidateList
 *
 * @description Identifies the individuals who are candidates for being the owner of the task.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/task-candidateList', fhirVersion: 'R4B')]
class CandidateListExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference $valueReference = null,
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
