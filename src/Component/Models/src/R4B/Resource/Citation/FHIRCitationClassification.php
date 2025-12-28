<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;

/**
 * @description The assignment to an organizing scheme.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.classification', fhirVersion: 'R4B')]
class FHIRCitationClassification extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type The kind of classifier (e.g. publication type, keyword) */
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> classifier The specific classification value */
        public array $classifier = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
