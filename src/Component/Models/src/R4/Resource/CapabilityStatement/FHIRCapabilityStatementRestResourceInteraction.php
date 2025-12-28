<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRMarkdown;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies a restful operation supported by the solution.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest.resource.interaction', fhirVersion: 'R4')]
class FHIRCapabilityStatementRestResourceInteraction extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRTypeRestfulInteractionType|null code read | vread | update | patch | delete | history-instance | history-type | create | search-type */
        #[NotBlank]
        public ?FHIRTypeRestfulInteractionType $code = null,
        /** @var FHIRMarkdown|null documentation Anything special about operation behavior */
        public ?FHIRMarkdown $documentation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
