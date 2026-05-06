<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/characteristicExpression
 *
 * @description An expression that defines the criteria for group membership. This extension can only be used on a Group resource with a membership of `definitional`. When this expression is used, the Group resource cannot have any characteristic elements; this mechanism is exclusive with characteristics. The result of the expression SHALL be boolean-valued. The expression SHALL be parameterized with a `%context` variable that represents the subject being tested for membership. If evaluation of the expression results in `true`, the subject is considered a member of the group.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/characteristicExpression', fhirVersion: 'R5')]
class CharacteristicExpressionExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/characteristicExpression',
            value: $this->valueExpression,
        );
    }
}
