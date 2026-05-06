<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-initialValue
 *
 * @description An expression that determines an initial value for the element on which it appears. The expression may be simply the name of a expression in a referenced library, or it may be a complete inline expression.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-initialValue', fhirVersion: 'R5')]
class InitialValueExtension extends Extension
{
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
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-initialValue',
            value: $this->valueExpression,
        );
    }
}
