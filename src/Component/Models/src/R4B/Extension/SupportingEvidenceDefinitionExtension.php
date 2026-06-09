<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-supportingEvidenceDefinition
 *
 * @description Specifies an expression that when evaluated, returns supporting evidence. The expression may be the name of an expression defined in a measure library. If qualified it may be the name of an expression defined in any library referenced by a measure library. The expression is allowed to be a definition, or a function that takes a single argument of the same type as the population basis of the population in which it appears, in the same way that continuous variable measure observations function. The expression may return any type. The name element of the expression is required, and is used to provide a link from the MeasureReport back to the supporting evidence definition. The description element of the expression is optional, but if specified, is expected to be populated in the resulting supporting evidence in the measure report. Note also that the expression-coding extension may be used to provide a code on the expression if desired, and if specified is expected to be populated in the resulting supporting evidence.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-supportingEvidenceDefinition', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'Measure.group.population')]
class SupportingEvidenceDefinitionExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var Expression|null valueExpression Value of extension */
        #[FhirProperty(fhirType: 'Expression', propertyKind: 'complex')]
        public ?Expression $valueExpression = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-supportingEvidenceDefinition',
            value: $this->valueExpression,
        );
    }
}
