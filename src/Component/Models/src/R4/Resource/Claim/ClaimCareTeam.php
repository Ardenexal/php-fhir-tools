<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The members of the team who provided the products and services.
 */
#[FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.careTeam', fhirVersion: 'R4')]
class ClaimCareTeam extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var PositiveIntPrimitive|null sequence Order of care team */
        #[NotBlank]
        public ?PositiveIntPrimitive $sequence = null,
        /** @var Reference|null provider Practitioner or organization */
        #[NotBlank]
        public ?Reference $provider = null,
        /** @var bool|null responsible Indicator of the lead practitioner */
        public ?bool $responsible = null,
        /** @var CodeableConcept|null role Function within the team */
        public ?CodeableConcept $role = null,
        /** @var CodeableConcept|null qualification Practitioner credential or specialization */
        public ?CodeableConcept $qualification = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
