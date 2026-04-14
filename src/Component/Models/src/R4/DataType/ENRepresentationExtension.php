<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven International (Modeling and Methodology)
 *
 * @see http://hl7.org/fhir/StructureDefinition/iso21090-EN-representation
 *
 * @description Name Representation.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/iso21090-EN-representation', fhirVersion: 'R4')]
class ENRepresentationExtension extends Extension
{
    public function __construct(
        /** @var CodePrimitive|null valueCode Value of extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $valueCode = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/iso21090-EN-representation',
            value: $this->valueCode,
        );
    }
}
