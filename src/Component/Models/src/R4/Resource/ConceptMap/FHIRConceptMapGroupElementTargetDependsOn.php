<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A set of additional dependencies for this mapping to hold. This mapping is only applicable if the specified element can be resolved, and it has the specified value.
 */
#[FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.element.target.dependsOn', fhirVersion: 'R4')]
class FHIRConceptMapGroupElementTargetDependsOn extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUri|null property Reference to property mapping depends on */
        #[NotBlank]
        public ?\FHIRUri $property = null,
        /** @var FHIRCanonical|null system Code System (if necessary) */
        public ?\FHIRCanonical $system = null,
        /** @var FHIRString|string|null value Value of the referenced element */
        #[NotBlank]
        public \FHIRString|string|null $value = null,
        /** @var FHIRString|string|null display Display for the code (if value is a code) */
        public \FHIRString|string|null $display = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
