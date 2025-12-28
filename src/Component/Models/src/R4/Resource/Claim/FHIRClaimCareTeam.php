<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The members of the team who provided the products and services.
 */
#[FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.careTeam', fhirVersion: 'R4')]
class FHIRClaimCareTeam extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null sequence Order of care team */
        #[NotBlank]
        public ?FHIRPositiveInt $sequence = null,
        /** @var FHIRReference|null provider Practitioner or organization */
        #[NotBlank]
        public ?FHIRReference $provider = null,
        /** @var FHIRBoolean|null responsible Indicator of the lead practitioner */
        public ?FHIRBoolean $responsible = null,
        /** @var FHIRCodeableConcept|null role Function within the team */
        public ?FHIRCodeableConcept $role = null,
        /** @var FHIRCodeableConcept|null qualification Practitioner credential or specialization */
        public ?FHIRCodeableConcept $qualification = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
