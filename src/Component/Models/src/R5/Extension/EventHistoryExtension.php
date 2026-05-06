<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/event-eventHistory
 *
 * @description Links to *Provenance* records for past versions of this resource that document  key state transitions or updates that are deemed "relevant" or important to a user looking at the current version of the resource. E.g, when an observation was verified or corrected.  This extension does not point to the Provenance associated with the current version of the resource - as it would be created after this version existed. The *Provenance* for the current version can be retrieved with a [` _revinclude`](search.html#revinclude).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/event-eventHistory', fhirVersion: 'R5')]
class EventHistoryExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/event-eventHistory',
            value: $this->valueReference,
        );
    }
}
