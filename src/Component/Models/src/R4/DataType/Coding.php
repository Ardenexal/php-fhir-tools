<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Coding
 *
 * @description A reference to a code defined by a terminology system.
 */
#[FHIRComplexType(typeName: 'Coding', fhirVersion: 'R4')]
class Coding extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var UriPrimitive|null system Identity of the terminology system */
        public ?UriPrimitive $system = null,
        /** @var StringPrimitive|string|null version Version of the system - if relevant */
        public StringPrimitive|string|null $version = null,
        /** @var CodePrimitive|null code Symbol in syntax defined by the system */
        public ?CodePrimitive $code = null,
        /** @var StringPrimitive|string|null display Representation defined by the system */
        public StringPrimitive|string|null $display = null,
        /** @var bool|null userSelected If this coding was chosen directly by the user */
        public ?bool $userSelected = null,
    ) {
        parent::__construct($id, $extension);
    }
}
