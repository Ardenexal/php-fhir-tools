<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Additional names for the study.
 */
#[FHIRBackboneElement(parentResource: 'ResearchStudy', elementPath: 'ResearchStudy.label', fhirVersion: 'R5')]
class FHIRResearchStudyLabel extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type primary | official | scientific | plain-language | subtitle | short-title | acronym | earlier-title | language | auto-translated | human-use | machine-use | duplicate-uid */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRString|string|null value The name */
        public \FHIRString|string|null $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
