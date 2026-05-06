<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\SpecimenDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;

/**
 * @description Set of instructions for preservation/transport of the specimen at a defined temperature interval, prior the testing process.
 */
#[FHIRBackboneElement(parentResource: 'SpecimenDefinition', elementPath: 'SpecimenDefinition.typeTested.handling', fhirVersion: 'R5')]
class SpecimenDefinitionTypeTestedHandling extends BackboneElement
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
        /** @var CodeableConcept|null temperatureQualifier Qualifies the interval of temperature */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $temperatureQualifier = null,
        /** @var Range|null temperatureRange Temperature range for these handling instructions */
        #[FhirProperty(fhirType: 'Range', propertyKind: 'complex')]
        public ?Range $temperatureRange = null,
        /** @var Duration|null maxDuration Maximum preservation time */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?Duration $maxDuration = null,
        /** @var MarkdownPrimitive|null instruction Preservation instruction */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $instruction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
