<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Relationships that this composition has with other compositions or documents that already exist.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'EvidenceReport', elementPath: 'EvidenceReport.relatesTo', fhirVersion: 'R4B')]
class FHIREvidenceReportRelatesTo extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReportRelationshipTypeType|null code replaces | amends | appends | transforms | replacedWith | amendedWith | appendedWith | transformedWith */
        #[NotBlank]
        public ?FHIRReportRelationshipTypeType $code = null,
        /** @var FHIRIdentifier|FHIRReference|null targetX Target of the relationship */
        #[NotBlank]
        public FHIRIdentifier|FHIRReference|null $targetX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
