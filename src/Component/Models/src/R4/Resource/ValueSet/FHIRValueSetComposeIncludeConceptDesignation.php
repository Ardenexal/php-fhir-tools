<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Additional representations for this concept when used in this value set - other languages, aliases, specialized purposes, used for particular purposes, etc.
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.compose.include.concept.designation', fhirVersion: 'R4')]
class FHIRValueSetComposeIncludeConceptDesignation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var string|null language Human language of the designation */
        public ?string $language = null,
        /** @var FHIRCoding|null use Types of uses of designations */
        public ?\FHIRCoding $use = null,
        /** @var FHIRString|string|null value The text value for this designation */
        #[NotBlank]
        public \FHIRString|string|null $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
