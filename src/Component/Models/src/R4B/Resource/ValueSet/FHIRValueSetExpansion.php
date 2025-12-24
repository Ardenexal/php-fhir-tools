<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A value set can also be "expanded", where the value set is turned into a simple collection of enumerated codes. This element holds the expansion, if it has been performed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.expansion', fhirVersion: 'R4B')]
class FHIRValueSetExpansion extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUri|null identifier Identifies the value set expansion (business identifier) */
        public ?FHIRUri $identifier = null,
        /** @var FHIRDateTime|null timestamp Time ValueSet expansion happened */
        #[NotBlank]
        public ?FHIRDateTime $timestamp = null,
        /** @var FHIRInteger|null total Total number of codes in the expansion */
        public ?FHIRInteger $total = null,
        /** @var FHIRInteger|null offset Offset at which this resource starts */
        public ?FHIRInteger $offset = null,
        /** @var array<FHIRValueSetExpansionParameter> parameter Parameter that controlled the expansion process */
        public array $parameter = [],
        /** @var array<FHIRValueSetExpansionContains> contains Codes in the value set */
        public array $contains = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
