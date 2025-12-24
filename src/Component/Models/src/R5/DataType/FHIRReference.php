<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Reference
 *
 * @description A reference from one resource to another.
 */
#[FHIRComplexType(typeName: 'Reference', fhirVersion: 'R5')]
class FHIRReference extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRString|string|null reference Literal reference, Relative, internal or absolute URL */
        public FHIRString|string|null $reference = null,
        /** @var FHIRUri|null type Type the reference refers to (e.g. "Patient") - must be a resource in resources */
        public ?FHIRUri $type = null,
        /** @var FHIRIdentifier|null identifier Logical reference, when literal reference is not known */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRString|string|null display Text alternative for the resource */
        public FHIRString|string|null $display = null,
    ) {
        parent::__construct($id, $extension);
    }
}
