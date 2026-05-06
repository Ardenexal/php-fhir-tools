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
 * @see http://hl7.org/fhir/StructureDefinition/referencesContained
 *
 * @description This indicates that the element containing this extension has an expression, xhtml, or some other content that makes reference to the specified contained resource in a way that is not detectable by the dom-3 constraint.  This extension is included to make the reference 'visible' to the dom-3 constraint.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/referencesContained', fhirVersion: 'R4')]
class ReferencesContainedExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/referencesContained',
            value: $this->valueReference,
        );
    }
}
