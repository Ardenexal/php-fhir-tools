<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - [WG Name] WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/operationoutcome-authority
 *
 * @description A reference to where the rule is defined (based on the authoritative URLs in the applicable conformance resources).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/operationoutcome-authority', fhirVersion: 'R4B')]
class AuthorityExtension extends Extension
{
    public function __construct(
        /** @var UriPrimitive|null valueUri Value of extension */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive $valueUri = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/operationoutcome-authority',
            value: $this->valueUri,
        );
    }
}
