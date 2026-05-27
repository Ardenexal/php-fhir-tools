<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UrlPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/entered-in-error-status
 *
 * @description This extension is for canonical resources to indicate how a erroneous resource is recorded. In many cases, erroneous records are simply deleted, but in some circumstances in clinical records, once an erroneous record has been created, it must be retained but marked as an having been created in error.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/entered-in-error-status', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'CanonicalResource')]
class EnteredInErrorStatusExtension extends Extension
{
    public function __construct(
        /** @var UrlPrimitive|null valueUrl Value of extension */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive')]
        public ?UrlPrimitive $valueUrl = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/entered-in-error-status',
            value: $this->valueUrl,
        );
    }
}
