<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A product specific contact, person (in a role), or an organization.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicinalProductDefinition', elementPath: 'MedicinalProductDefinition.contact', fhirVersion: 'R4B')]
class FHIRMedicinalProductDefinitionContact extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Allows the contact to be classified, for example QPPV, Pharmacovigilance Enquiry Information */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRReference|null contact A product specific contact, person (in a role), or an organization */
        #[NotBlank]
        public ?FHIRReference $contact = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
