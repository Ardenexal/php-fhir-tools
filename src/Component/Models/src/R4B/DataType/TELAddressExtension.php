<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven International (Modeling and Methodology)
 *
 * @see http://hl7.org/fhir/StructureDefinition/iso21090-TEL-address
 *
 * @description A V3 compliant, RFC 3966 conformant URI version of the telephone or fax number.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/iso21090-TEL-address', fhirVersion: 'R4B')]
class TELAddressExtension extends Extension
{
    public function __construct(
        /** @var UrlPrimitive|null valueUrl Value of extension */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UrlPrimitive $valueUrl = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/iso21090-TEL-address',
            value: $this->valueUrl,
        );
    }
}
