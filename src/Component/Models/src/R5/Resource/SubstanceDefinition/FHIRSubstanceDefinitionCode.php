<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Codes associated with the substance.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceDefinition', elementPath: 'SubstanceDefinition.code', fhirVersion: 'R5')]
class FHIRSubstanceDefinitionCode extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code The specific code */
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRCodeableConcept|null status Status of the code assignment, for example 'provisional', 'approved' */
        public ?\FHIRCodeableConcept $status = null,
        /** @var FHIRDateTime|null statusDate The date at which the code status was changed */
        public ?\FHIRDateTime $statusDate = null,
        /** @var array<FHIRAnnotation> note Any comment can be provided in this field */
        public array $note = [],
        /** @var array<FHIRReference> source Supporting literature */
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
