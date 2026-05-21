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
 * @see http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-intendedRecipient
 *
 * @description The biologicallyderivedproduct-intendedRecipient extension can be used to record the intended recipient of the BiologicallyDerivedProduct to satisfy product traceability requirements, and does not represent administration, nor does it prevent administration to a different recipient at a future date. Further detail on the traceability requirements can be found in the BiologicallyDerivedProduct resource [Intended Recipients](http://hl7.org/fhir/biologicallyderivedproduct.html#intendedrecipient).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-intendedRecipient', fhirVersion: 'R4')]
class BDPIntendedRecipientExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-intendedRecipient',
            value: $this->valueReference,
        );
    }
}
