<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @description The target composition/document of this relationship.
 */
#[FHIRBackboneElement(parentResource: 'EvidenceReport', elementPath: 'EvidenceReport.relatesTo.target', fhirVersion: 'R5')]
class FHIREvidenceReportRelatesToTarget extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Target of the relationship URL */
        public ?FHIRUri $url = null,
        /** @var FHIRIdentifier|null identifier Target of the relationship Identifier */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRMarkdown|null display Target of the relationship Display */
        public ?FHIRMarkdown $display = null,
        /** @var FHIRReference|null resource Target of the relationship Resource reference */
        public ?FHIRReference $resource = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
