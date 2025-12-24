<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;

/**
 * @description Other claims which are related to this claim such as prior submissions or claims for related services or for the same event.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.related', fhirVersion: 'R4B')]
class FHIRClaimRelated extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null claim Reference to the related claim */
        public ?FHIRReference $claim = null,
        /** @var FHIRCodeableConcept|null relationship How the reference claim is related */
        public ?FHIRCodeableConcept $relationship = null,
        /** @var FHIRIdentifier|null reference File or case reference */
        public ?FHIRIdentifier $reference = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
