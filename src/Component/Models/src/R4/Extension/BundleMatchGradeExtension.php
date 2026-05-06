<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/match-grade
 *
 * @description Assessment of resource match outcome - how likely this resource is to be a match.
 *
 * This extension is intended to supplement the search score with a coded value that can provide more discrete values than a numeric 'ordering' style score.
 *
 * A score is not required to use the grade.
 *
 * This is returned by the Patient $match operation (though could be used by similar operations on other resource types).
 *
 * The codes 'probable' and 'possible' although similar, indicate the level of confidence the matching engine has that the result is a match. This could be through the number of matching fields, or other similar levels of certainty. Probable indicates that the match is likely, however  possible indicates it had some partial matching data.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/match-grade', fhirVersion: 'R4')]
class BundleMatchGradeExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/match-grade',
            value: $this->valueCode,
        );
    }
}
