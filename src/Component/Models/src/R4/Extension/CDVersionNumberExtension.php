<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 International / Structured Documents
 *
 * @see http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-versionNumber
 *
 * @description Version specific identifier for the composition, assigned when each version is created/updated. Note: this extension is deprecated because since R5 Composition has a version element which should be used instead.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-versionNumber', fhirVersion: 'R4')]
class CDVersionNumberExtension extends Extension
{
    public function __construct(
        /** @var StringPrimitive|null valueString Value of extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $valueString = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-versionNumber',
            value: $this->valueString,
        );
    }
}
