<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Immunization;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;

/**
 * @description Categorical data indicating that an adverse event is associated in time to an immunization.
 */
#[FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.reaction', fhirVersion: 'R5')]
class ImmunizationReaction extends BackboneElement
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
        /** @var DateTimePrimitive|null date When reaction started */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $date = null,
        /** @var CodeableReference|null manifestation Additional information on reaction */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $manifestation = null,
        /** @var bool|null reported Indicates self-reported reaction */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $reported = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
