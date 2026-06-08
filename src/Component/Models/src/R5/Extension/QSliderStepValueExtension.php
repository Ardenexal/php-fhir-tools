<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRContextInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/questionnaire-sliderStepValue
 *
 * @description For slider-based controls, indicates the step size to use when toggling the control up or down.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-sliderStepValue', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'Questionnaire.item')]
#[FHIRExtensionContext(type: 'element', expression: 'ElementDefinition')]
#[FHIRContextInvariant(expression: 'ofType(ElementDefinition).type.exists(code=\'integer\') or where(%resource.is(Questionnaire)).exists(type.first()=\'integer\')')]
class QSliderStepValueExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var int|null valueInteger Value of extension */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $valueInteger = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-sliderStepValue',
            value: $this->valueInteger,
        );
    }
}
