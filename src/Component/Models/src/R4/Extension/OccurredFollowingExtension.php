<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/condition-occurredFollowing
 *
 * @description Further conditions, problems, diagnoses, procedures or events or the substance that preceded this Condition.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/condition-occurredFollowing', fhirVersion: 'R4')]
class OccurredFollowingExtension extends Extension
{
    public function __construct(
        /** @var CodeableConcept|Reference|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null) */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/condition-occurredFollowing',
            value: $this->value,
        );
    }
}
