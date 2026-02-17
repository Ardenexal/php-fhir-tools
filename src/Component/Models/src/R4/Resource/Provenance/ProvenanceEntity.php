<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Provenance;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ProvenanceEntityRoleType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An entity used in this activity.
 */
#[FHIRBackboneElement(parentResource: 'Provenance', elementPath: 'Provenance.entity', fhirVersion: 'R4')]
class ProvenanceEntity extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var ProvenanceEntityRoleType|null role derivation | revision | quotation | source | removal */
        #[NotBlank]
        public ?ProvenanceEntityRoleType $role = null,
        /** @var Reference|null what Identity of entity */
        #[NotBlank]
        public ?Reference $what = null,
        /** @var array<ProvenanceAgent> agent Entity is attributed to this agent */
        public array $agent = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
