<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\EncounterHistory;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The location of the patient at this point in the encounter, the multiple cardinality permits de-normalizing the levels of the location hierarchy, such as site/ward/room/bed.
 */
#[FHIRBackboneElement(parentResource: 'EncounterHistory', elementPath: 'EncounterHistory.location', fhirVersion: 'R5')]
class EncounterHistoryLocation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var Reference|null location Location the encounter takes place */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $location = null,
        /** @var CodeableConcept|null form The physical type of the location (usually the level in the location hierarchy - bed, room, ward, virtual etc.) */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $form = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
