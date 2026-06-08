<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/rendered-value
 *
 * @description Provides a rendered version of the value intended for human display.  For example, a sensitive identifier (e.g. social security number) partially obscured by asterisks; a drivers licence number with dashes inserted; a date formatted as MMM dd, yyyy; etc.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/rendered-value', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'Identifier.value')]
#[FHIRExtensionContext(type: 'element', expression: 'date')]
#[FHIRExtensionContext(type: 'element', expression: 'dateTime')]
#[FHIRExtensionContext(type: 'element', expression: 'time')]
#[FHIRExtensionContext(type: 'element', expression: 'instant')]
#[FHIRExtensionContext(type: 'element', expression: 'integer')]
#[FHIRExtensionContext(type: 'element', expression: 'decimal')]
#[FHIRExtensionContext(type: 'element', expression: 'string')]
#[FHIRExtensionContext(type: 'element', expression: 'code')]
class RenderedValueExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var StringPrimitive|null valueString Value of extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $valueString = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/rendered-value',
            value: $this->valueString,
        );
    }
}
