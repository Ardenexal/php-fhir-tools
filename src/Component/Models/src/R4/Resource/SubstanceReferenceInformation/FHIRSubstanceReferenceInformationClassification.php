<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Todo.
 */
#[FHIRBackboneElement(
    parentResource: 'SubstanceReferenceInformation',
    elementPath: 'SubstanceReferenceInformation.classification',
    fhirVersion: 'R4',
)]
class FHIRSubstanceReferenceInformationClassification extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null domain Todo */
        public ?\FHIRCodeableConcept $domain = null,
        /** @var FHIRCodeableConcept|null classification Todo */
        public ?\FHIRCodeableConcept $classification = null,
        /** @var array<FHIRCodeableConcept> subtype Todo */
        public array $subtype = [],
        /** @var array<FHIRReference> source Todo */
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
