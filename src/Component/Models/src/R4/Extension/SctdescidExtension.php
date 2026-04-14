<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR Core WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/coding-sctdescid
 *
 * @description The SNOMED CT Description ID for the display.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/coding-sctdescid', fhirVersion: 'R4')]
class SctdescidExtension extends Extension
{
    public function __construct(
        /** @var IdPrimitive|null valueId Value of extension */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $valueId = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/coding-sctdescid',
            value: $this->valueId,
        );
    }
}
