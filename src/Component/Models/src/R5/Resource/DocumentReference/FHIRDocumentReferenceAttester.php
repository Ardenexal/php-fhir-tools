<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A participant who has authenticated the accuracy of the document.
 */
#[FHIRBackboneElement(parentResource: 'DocumentReference', elementPath: 'DocumentReference.attester', fhirVersion: 'R5')]
class FHIRDocumentReferenceAttester extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
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
        public ?\FHIRCodeableConcept $mode = null,
        /** @var FHIRDateTime|null time When the document was attested */
        public ?\FHIRDateTime $time = null,
        /** @var FHIRReference|null party Who attested the document */
        public ?\FHIRReference $party = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
