<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @see http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-reflexivity
 *
 * @description Indicates if the association always holds for a concept with itself (refexive), never holds for a concept with itself (irreflexive)
 */
#[FHIRExtensionDefinition(url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-reflexivity', fhirVersion: 'R4B')]
class SupportedConceptRelationshipReflexivityExtension extends Extension
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
            url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-reflexivity',
            value: $this->valueCode,
        );
    }
}
