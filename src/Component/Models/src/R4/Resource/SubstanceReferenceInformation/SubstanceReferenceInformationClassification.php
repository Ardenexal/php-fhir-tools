<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceReferenceInformation;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @description Todo.
 */
#[FHIRBackboneElement(
    parentResource: 'SubstanceReferenceInformation',
    elementPath: 'SubstanceReferenceInformation.classification',
    fhirVersion: 'R4',
)]
class SubstanceReferenceInformationClassification extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null domain Todo */
        public ?CodeableConcept $domain = null,
        /** @var CodeableConcept|null classification Todo */
        public ?CodeableConcept $classification = null,
        /** @var array<CodeableConcept> subtype Todo */
        public array $subtype = [],
        /** @var array<Reference> source Todo */
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
