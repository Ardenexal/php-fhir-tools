<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A value set can also be "expanded", where the value set is turned into a simple collection of enumerated codes. This element holds the expansion, if it has been performed.
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.expansion', fhirVersion: 'R4')]
class ValueSetExpansion extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var UriPrimitive|null identifier Identifies the value set expansion (business identifier) */
        public ?UriPrimitive $identifier = null,
        /** @var DateTimePrimitive|null timestamp Time ValueSet expansion happened */
        #[NotBlank]
        public ?DateTimePrimitive $timestamp = null,
        /** @var int|null total Total number of codes in the expansion */
        public ?int $total = null,
        /** @var int|null offset Offset at which this resource starts */
        public ?int $offset = null,
        /** @var array<ValueSetExpansionParameter> parameter Parameter that controlled the expansion process */
        public array $parameter = [],
        /** @var array<ValueSetExpansionContains> contains Codes in the value set */
        public array $contains = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
