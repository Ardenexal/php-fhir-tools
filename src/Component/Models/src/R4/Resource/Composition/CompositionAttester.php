<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Composition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CompositionAttestationModeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A participant who has attested to the accuracy of the composition/document.
 */
#[FHIRBackboneElement(parentResource: 'Composition', elementPath: 'Composition.attester', fhirVersion: 'R4')]
class CompositionAttester extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CompositionAttestationModeType|null mode personal | professional | legal | official */
        #[NotBlank]
        public ?CompositionAttestationModeType $mode = null,
        /** @var DateTimePrimitive|null time When the composition was attested */
        public ?DateTimePrimitive $time = null,
        /** @var Reference|null party Who attested the composition */
        public ?Reference $party = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
