<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An entity used in this activity.
 */
#[FHIRBackboneElement(parentResource: 'Provenance', elementPath: 'Provenance.entity', fhirVersion: 'R5')]
class FHIRProvenanceEntity extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRProvenanceEntityRoleType|null role revision | quotation | source | instantiates | removal */
        #[NotBlank]
        public ?FHIRProvenanceEntityRoleType $role = null,
        /** @var FHIRReference|null what Identity of entity */
        #[NotBlank]
        public ?FHIRReference $what = null,
        /** @var array<FHIRProvenanceAgent> agent Entity is attributed to this agent */
        public array $agent = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
