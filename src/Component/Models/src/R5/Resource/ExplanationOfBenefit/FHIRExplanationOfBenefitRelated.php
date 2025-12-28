<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Other claims which are related to this claim such as prior submissions or claims for related services or for the same event.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.related', fhirVersion: 'R5')]
class FHIRExplanationOfBenefitRelated extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null claim Reference to the related claim */
        public ?\FHIRReference $claim = null,
        /** @var FHIRCodeableConcept|null relationship How the reference claim is related */
        public ?\FHIRCodeableConcept $relationship = null,
        /** @var FHIRIdentifier|null reference File or case reference */
        public ?\FHIRIdentifier $reference = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
