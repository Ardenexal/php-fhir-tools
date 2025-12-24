<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A definition of the restful capabilities of the solution, if any.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest', fhirVersion: 'R5')]
class FHIRCapabilityStatementRest extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRRestfulCapabilityModeType|null mode client | server */
        #[NotBlank]
        public ?FHIRRestfulCapabilityModeType $mode = null,
        /** @var FHIRMarkdown|null documentation General description of implementation */
        public ?FHIRMarkdown $documentation = null,
        /** @var FHIRCapabilityStatementRestSecurity|null security Information about security of implementation */
        public ?FHIRCapabilityStatementRestSecurity $security = null,
        /** @var array<FHIRCapabilityStatementRestResource> resource Resource served on the REST interface */
        public array $resource = [],
        /** @var array<FHIRCapabilityStatementRestInteraction> interaction What operations are supported? */
        public array $interaction = [],
        /** @var array<FHIRCapabilityStatementRestResourceSearchParam> searchParam Search parameters for searching all resources */
        public array $searchParam = [],
        /** @var array<FHIRCapabilityStatementRestResourceOperation> operation Definition of a system level operation */
        public array $operation = [],
        /** @var array<FHIRCanonical> compartment Compartments served/used by system */
        public array $compartment = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
