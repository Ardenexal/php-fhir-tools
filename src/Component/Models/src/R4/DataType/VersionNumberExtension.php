<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - Structured Documents WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-versionNumber
 *
 * @description Version specific identifier for the composition, assigned when each version is created/updated.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-versionNumber', fhirVersion: 'R4')]
class VersionNumberExtension extends Extension
{
    public function __construct(
        /** @var StringPrimitive|null valueString Value of extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $valueString = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-versionNumber',
            value: $this->valueString,
        );
    }
}
