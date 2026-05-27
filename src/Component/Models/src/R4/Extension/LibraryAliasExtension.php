<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-libraryAlias
 *
 * @description Specifies the alias to be used for the library on which it appears. This is useful when multiple libraries have the same unqualified name, and allows in-line CQL expressions to refer to the libraries via their alias, rather than the default of the unqualified name of the library.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-libraryAlias', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.library')]
#[FHIRExtensionContext(type: 'element', expression: 'PlanDefinition.library')]
#[FHIRExtensionContext(type: 'element', expression: 'Measure.library')]
#[FHIRExtensionContext(type: 'extension', expression: 'http://hl7.org/fhir/StructureDefinition/cqf-library')]
class LibraryAliasExtension extends Extension
{
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
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-libraryAlias',
            value: $this->valueString,
        );
    }
}
