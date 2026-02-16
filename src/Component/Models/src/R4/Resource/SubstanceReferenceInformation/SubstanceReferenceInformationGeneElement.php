<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceReferenceInformation;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @description Todo.
 */
#[FHIRBackboneElement(
    parentResource: 'SubstanceReferenceInformation',
    elementPath: 'SubstanceReferenceInformation.geneElement',
    fhirVersion: 'R4',
)]
class SubstanceReferenceInformationGeneElement extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Todo */
        public ?CodeableConcept $type = null,
        /** @var Identifier|null element Todo */
        public ?Identifier $element = null,
        /** @var array<Reference> source Todo */
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
