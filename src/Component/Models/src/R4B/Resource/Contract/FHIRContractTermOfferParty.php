<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Offer Recipient.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.offer.party', fhirVersion: 'R4B')]
class FHIRContractTermOfferParty extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRReference> reference Referenced entity */
        public array $reference = [],
        /** @var FHIRCodeableConcept|null role Participant engagement type */
        #[NotBlank]
        public ?\FHIRCodeableConcept $role = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
