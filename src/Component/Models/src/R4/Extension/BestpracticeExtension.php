<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR Core WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/elementdefinition-bestpractice
 *
 * @description Mark that an invariant represents 'best practice' rule - a rule that implementers may choose to enforce at error level in some or all circumstances.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-bestpractice', fhirVersion: 'R4')]
class BestpracticeExtension extends Extension
{
    public function __construct(
        /** @var bool|CodeableConcept|null value Value of extension (bool|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|null) */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        public ?bool $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-bestpractice',
            value: $this->value,
        );
    }
}
