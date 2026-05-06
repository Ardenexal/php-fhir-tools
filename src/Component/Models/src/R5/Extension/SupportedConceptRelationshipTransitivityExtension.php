<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @see http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-transitivity
 *
 * @description Indicates whether the relationship always (transitive) or never (antitransitive) propagates such that if the association exists from A to B and from B to C that the relationship can be inferred to exist from A to C
 */
#[FHIRExtensionDefinition(url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-transitivity', fhirVersion: 'R5')]
class SupportedConceptRelationshipTransitivityExtension extends Extension
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
            url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-transitivity',
            value: $this->valueCode,
        );
    }
}
