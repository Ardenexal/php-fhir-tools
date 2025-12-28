<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The contact for the health insurance product for a certain purpose.
 */
#[FHIRBackboneElement(parentResource: 'InsurancePlan', elementPath: 'InsurancePlan.contact', fhirVersion: 'R4')]
class FHIRInsurancePlanContact extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null purpose The type of contact */
        public ?\FHIRCodeableConcept $purpose = null,
        /** @var FHIRHumanName|null name A name associated with the contact */
        public ?\FHIRHumanName $name = null,
        /** @var array<FHIRContactPoint> telecom Contact details (telephone, email, etc.)  for a contact */
        public array $telecom = [],
        /** @var FHIRAddress|null address Visiting or postal addresses for the contact */
        public ?\FHIRAddress $address = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
