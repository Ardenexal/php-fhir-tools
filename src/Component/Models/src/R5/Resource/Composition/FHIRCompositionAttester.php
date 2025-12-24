<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A participant who has attested to the accuracy of the composition/document.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Composition', elementPath: 'Composition.attester', fhirVersion: 'R5')]
class FHIRCompositionAttester extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null mode personal | professional | legal | official */
        #[NotBlank]
        public ?FHIRCodeableConcept $mode = null,
        /** @var FHIRDateTime|null time When the composition was attested */
        public ?FHIRDateTime $time = null,
        /** @var FHIRReference|null party Who attested the composition */
        public ?FHIRReference $party = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
