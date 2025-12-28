<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The contributing factors suspected to have increased the probability or severity of the adverse event.
 */
#[FHIRBackboneElement(parentResource: 'AdverseEvent', elementPath: 'AdverseEvent.contributingFactor', fhirVersion: 'R5')]
class FHIRAdverseEventContributingFactor extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|FHIRCodeableConcept|null itemX Item suspected to have increased the probability or severity of the adverse event */
        #[NotBlank]
        public \FHIRReference|\FHIRCodeableConcept|null $itemX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
