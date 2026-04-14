<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR Core WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/match-grade
 *
 * @description Assessment of resource match outcome - how likely this resource is to be a match.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/match-grade', fhirVersion: 'R4B')]
class MatchGradeExtension extends Extension
{
    public function __construct(
        /** @var CodePrimitive|null valueCode Value of extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive $valueCode = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/match-grade',
            value: $this->valueCode,
        );
    }
}
