<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/list-for
 *
 * @description Indicates the entity for whose benefit the List is created and maintained.  This is used when the intended beneficiary of the List is distinct from whoever authored it.  For example, if a capitation List is maintained by a central organization of the patients for a particular provider, the List.source would be the central organization, while the 'List for' extension would point to the provider.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/list-for', fhirVersion: 'R4')]
class ListForExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/list-for',
            value: $this->valueReference,
        );
    }
}
