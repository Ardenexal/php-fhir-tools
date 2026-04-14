<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/familymemberhistory-abatement
 *
 * @description The approximate date, age, or flag indicating that the condition of the family member resolved. The abatement should only be specified if the condition is stated in the positive sense, i.e., the didNotHave flag is false.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/familymemberhistory-abatement', fhirVersion: 'R4B')]
class AbatementExtension extends Extension
{
    public function __construct(
        /** @var DatePrimitive|Age|bool|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Age|bool|null) */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DatePrimitive $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/familymemberhistory-abatement',
            value: $this->value,
        );
    }
}
