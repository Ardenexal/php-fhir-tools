<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @see http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-symmetry
 *
 * @description Indicates if the relationship always holds in the reverse direction as well (symetric), never holds in the reverse direction as well (antisymetric)
 */
#[FHIRExtensionDefinition(url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-symmetry', fhirVersion: 'R4')]
class SupportedConceptRelationshipSymmetryExtension extends Extension
{
    public function __construct(
        /** @var CodePrimitive|null valueCode Value of extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $valueCode = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-symmetry',
            value: $this->valueCode,
        );
    }
}
