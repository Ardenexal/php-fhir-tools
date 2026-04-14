<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\AdverseEvent;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;

/**
 * @description Information on the possible cause of the event.
 */
#[FHIRBackboneElement(parentResource: 'AdverseEvent', elementPath: 'AdverseEvent.suspectEntity.causality', fhirVersion: 'R5')]
class AdverseEventSuspectEntityCausality extends BackboneElement
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
        /** @var CodeableConcept|null assessmentMethod Method of evaluating the relatedness of the suspected entity to the event */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $assessmentMethod = null,
        /** @var CodeableConcept|null entityRelatedness Result of the assessment regarding the relatedness of the suspected entity to the event */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $entityRelatedness = null,
        /** @var Reference|null author Author of the information on the possible cause of the event */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $author = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
