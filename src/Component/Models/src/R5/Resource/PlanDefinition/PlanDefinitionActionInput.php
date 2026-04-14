<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\PlanDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\DataRequirement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @description Defines input data requirements for the action.
 */
#[FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.action.input', fhirVersion: 'R5')]
class PlanDefinitionActionInput extends BackboneElement
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
        /** @var StringPrimitive|string|null title User-visible title */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $title = null,
        /** @var DataRequirement|null requirement What data is provided */
        #[FhirProperty(fhirType: 'DataRequirement', propertyKind: 'complex')]
        public ?DataRequirement $requirement = null,
        /** @var IdPrimitive|null relatedData What data is provided */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $relatedData = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
