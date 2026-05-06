<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Terminology Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-expression
 *
 * @description An expression that provides an alternative definition of the content of the value set (compose). There are two different ways to use this expression extension: If both an expression (this extension) and a compose element is present, the compose is understood to make the same statement as the expression. If there is no compose, the expression is the only definition of the value set and the ValueSet definition (compose) can only be evaluated by a system that understands the syntax used in the expression.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-expression', fhirVersion: 'R4')]
class VSExpressionExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-expression',
            value: $this->valueExpression,
        );
    }
}
