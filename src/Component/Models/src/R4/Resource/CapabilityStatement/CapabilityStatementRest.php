<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RestfulCapabilityModeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A definition of the restful capabilities of the solution, if any.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest', fhirVersion: 'R4')]
class CapabilityStatementRest extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var RestfulCapabilityModeType|null mode client | server */
        #[NotBlank]
        public ?RestfulCapabilityModeType $mode = null,
        /** @var MarkdownPrimitive|null documentation General description of implementation */
        public ?MarkdownPrimitive $documentation = null,
        /** @var CapabilityStatementRestSecurity|null security Information about security of implementation */
        public ?CapabilityStatementRestSecurity $security = null,
        /** @var array<CapabilityStatementRestResource> resource Resource served on the REST interface */
        public array $resource = [],
        /** @var array<CapabilityStatementRestInteraction> interaction What operations are supported? */
        public array $interaction = [],
        /** @var array<CapabilityStatementRestResourceSearchParam> searchParam Search parameters for searching all resources */
        public array $searchParam = [],
        /** @var array<CapabilityStatementRestResourceOperation> operation Definition of a system level operation */
        public array $operation = [],
        /** @var array<CanonicalPrimitive> compartment Compartments served/used by system */
        public array $compartment = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
