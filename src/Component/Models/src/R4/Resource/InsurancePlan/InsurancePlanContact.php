<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\InsurancePlan;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName;

/**
 * @description The contact for the health insurance product for a certain purpose.
 */
#[FHIRBackboneElement(parentResource: 'InsurancePlan', elementPath: 'InsurancePlan.contact', fhirVersion: 'R4')]
class InsurancePlanContact extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null purpose The type of contact */
        public ?CodeableConcept $purpose = null,
        /** @var HumanName|null name A name associated with the contact */
        public ?HumanName $name = null,
        /** @var array<ContactPoint> telecom Contact details (telephone, email, etc.)  for a contact */
        public array $telecom = [],
        /** @var Address|null address Visiting or postal addresses for the contact */
        public ?Address $address = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
