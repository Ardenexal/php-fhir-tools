<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ChargeItemDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\RelatedArtifact;

/**
 * @description Expressions that describe applicability criteria for the billing code.
 */
#[FHIRBackboneElement(parentResource: 'ChargeItemDefinition', elementPath: 'ChargeItemDefinition.applicability', fhirVersion: 'R5')]
class ChargeItemDefinitionApplicability extends BackboneElement
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
        /** @var Expression|null condition Boolean-valued expression */
        #[FhirProperty(fhirType: 'Expression', propertyKind: 'complex')]
        public ?Expression $condition = null,
        /** @var Period|null effectivePeriod When the charge item definition is expected to be used */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $effectivePeriod = null,
        /** @var RelatedArtifact|null relatedArtifact Reference to / quotation of the external source of the group of properties */
        #[FhirProperty(fhirType: 'RelatedArtifact', propertyKind: 'complex')]
        public ?RelatedArtifact $relatedArtifact = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
