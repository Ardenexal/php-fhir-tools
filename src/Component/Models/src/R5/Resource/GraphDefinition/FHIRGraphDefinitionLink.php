<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Links this graph makes rules about.
 */
#[FHIRBackboneElement(parentResource: 'GraphDefinition', elementPath: 'GraphDefinition.link', fhirVersion: 'R5')]
class FHIRGraphDefinitionLink extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Why this link is specified */
        public \FHIRString|string|null $description = null,
        /** @var FHIRInteger|null min Minimum occurrences for this link */
        public ?\FHIRInteger $min = null,
        /** @var FHIRString|string|null max Maximum occurrences for this link */
        public \FHIRString|string|null $max = null,
        /** @var FHIRId|null sourceId Source Node for this link */
        #[NotBlank]
        public ?\FHIRId $sourceId = null,
        /** @var FHIRString|string|null path Path in the resource that contains the link */
        public \FHIRString|string|null $path = null,
        /** @var FHIRString|string|null sliceName Which slice (if profiled) */
        public \FHIRString|string|null $sliceName = null,
        /** @var FHIRId|null targetId Target Node for this link */
        #[NotBlank]
        public ?\FHIRId $targetId = null,
        /** @var FHIRString|string|null params Criteria for reverse lookup */
        public \FHIRString|string|null $params = null,
        /** @var array<FHIRGraphDefinitionLinkCompartment> compartment Compartment Consistency Rules */
        public array $compartment = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
