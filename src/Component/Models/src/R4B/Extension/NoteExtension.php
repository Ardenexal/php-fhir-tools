<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/note
 *
 * @description Additional notes that apply to this resource or element.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/note', fhirVersion: 'R4B')]
class NoteExtension extends Extension
{
    public function __construct(
        /** @var Annotation|null valueAnnotation Notes for this resource/element */
        #[FhirProperty(fhirType: 'Annotation', propertyKind: 'complex')]
        public ?Annotation $valueAnnotation = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/note',
            value: $this->valueAnnotation,
        );
    }
}
