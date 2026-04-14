<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-expression
 *
 * @description An expression that provides an alternative definition of the content of the value set. There are two different ways to use this expression extension: If both an expression and a compose element is present, the compose is understood the make the same statement as the expression. If there is no compose, the expression is the only definition of the value set, and the value set can only be processed by a server that understands the expression syntax, it that is computable.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-expression', fhirVersion: 'R4B')]
class ExpressionExtension extends Extension
{
    public function __construct(
        /** @var Expression|null valueExpression Value of extension */
        #[FhirProperty(fhirType: 'Expression', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Expression $valueExpression = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-expression',
            value: $this->valueExpression,
        );
    }
}
