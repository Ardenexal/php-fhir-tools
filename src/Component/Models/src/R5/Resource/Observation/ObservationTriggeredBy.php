<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Observation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\TriggeredBytypeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies the observation(s) that triggered the performance of this observation.
 */
#[FHIRBackboneElement(parentResource: 'Observation', elementPath: 'Observation.triggeredBy', fhirVersion: 'R5')]
class ObservationTriggeredBy extends BackboneElement
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
        /** @var Reference|null observation Triggering observation */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $observation = null,
        /** @var TriggeredBytypeType|null type reflex | repeat | re-run */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?TriggeredBytypeType $type = null,
        /** @var StringPrimitive|string|null reason Reason that the observation was triggered */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $reason = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
