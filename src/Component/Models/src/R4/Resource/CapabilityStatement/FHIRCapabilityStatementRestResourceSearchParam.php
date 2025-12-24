<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Search parameters for implementations to support and/or make use of - either references to ones defined in the specification, or additional ones defined for/by the implementation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest.resource.searchParam', fhirVersion: 'R4')]
class FHIRCapabilityStatementRestResourceSearchParam extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name Name of search parameter */
        #[NotBlank]
        public FHIRString|string|null $name = null,
        /** @var FHIRCanonical|null definition Source of definition for parameter */
        public ?FHIRCanonical $definition = null,
        /** @var FHIRSearchParamTypeType|null type number | date | string | token | reference | composite | quantity | uri | special */
        #[NotBlank]
        public ?FHIRSearchParamTypeType $type = null,
        /** @var FHIRMarkdown|null documentation Server-specific usage */
        public ?FHIRMarkdown $documentation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
