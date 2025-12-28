<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The actual statement of requirement, in markdown format.
 */
#[FHIRBackboneElement(parentResource: 'Requirements', elementPath: 'Requirements.statement', fhirVersion: 'R5')]
class FHIRRequirementsStatement extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null key Key that identifies this statement */
        #[NotBlank]
        public ?\FHIRId $key = null,
        /** @var FHIRString|string|null label Short Human label for this statement */
        public \FHIRString|string|null $label = null,
        /** @var array<FHIRConformanceExpectationType> conformance SHALL | SHOULD | MAY | SHOULD-NOT */
        public array $conformance = [],
        /** @var FHIRBoolean|null conditionality Set to true if requirements statement is conditional */
        public ?\FHIRBoolean $conditionality = null,
        /** @var FHIRMarkdown|null requirement The actual requirement */
        #[NotBlank]
        public ?\FHIRMarkdown $requirement = null,
        /** @var FHIRString|string|null derivedFrom Another statement this clarifies/restricts ([url#]key) */
        public \FHIRString|string|null $derivedFrom = null,
        /** @var FHIRString|string|null parent A larger requirement that this requirement helps to refine and enable */
        public \FHIRString|string|null $parent = null,
        /** @var array<FHIRUrl> satisfiedBy Design artifact that satisfies this requirement */
        public array $satisfiedBy = [],
        /** @var array<FHIRUrl> reference External artifact (rule/document etc. that) created this requirement */
        public array $reference = [],
        /** @var array<FHIRReference> source Who asked for this statement */
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
