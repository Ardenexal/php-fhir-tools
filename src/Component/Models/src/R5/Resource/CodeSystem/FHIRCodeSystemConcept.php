<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Concepts that are in the code system. The concept definitions are inherently hierarchical, but the definitions must be consulted to determine what the meanings of the hierarchical relationships are.
 */
#[FHIRBackboneElement(parentResource: 'CodeSystem', elementPath: 'CodeSystem.concept', fhirVersion: 'R5')]
class FHIRCodeSystemConcept extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null code Code that identifies concept */
        #[NotBlank]
        public ?FHIRCode $code = null,
        /** @var FHIRString|string|null display Text to display to the user */
        public FHIRString|string|null $display = null,
        /** @var FHIRString|string|null definition Formal definition */
        public FHIRString|string|null $definition = null,
        /** @var array<FHIRCodeSystemConceptDesignation> designation Additional representations for the concept */
        public array $designation = [],
        /** @var array<FHIRCodeSystemConceptProperty> property Property value for the concept */
        public array $property = [],
        /** @var array<FHIRCodeSystemConcept> concept Child Concepts (is-a/contains/categorizes) */
        public array $concept = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
