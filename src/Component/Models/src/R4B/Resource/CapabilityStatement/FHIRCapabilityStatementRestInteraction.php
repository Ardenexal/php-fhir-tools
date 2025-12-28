<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A specification of restful operations supported by the system.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest.interaction', fhirVersion: 'R4B')]
class FHIRCapabilityStatementRestInteraction extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRSystemRestfulInteractionType|null code transaction | batch | search-system | history-system */
        #[NotBlank]
        public ?\FHIRSystemRestfulInteractionType $code = null,
        /** @var FHIRMarkdown|null documentation Anything special about operation behavior */
        public ?\FHIRMarkdown $documentation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
