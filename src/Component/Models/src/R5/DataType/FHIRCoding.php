<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Coding
 *
 * @description A reference to a code defined by a terminology system.
 */
#[FHIRComplexType(typeName: 'Coding', fhirVersion: 'R5')]
class FHIRCoding extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRUri|null system Identity of the terminology system */
        public ?FHIRUri $system = null,
        /** @var FHIRString|string|null version Version of the system - if relevant */
        public FHIRString|string|null $version = null,
        /** @var FHIRCode|null code Symbol in syntax defined by the system */
        public ?FHIRCode $code = null,
        /** @var FHIRString|string|null display Representation defined by the system */
        public FHIRString|string|null $display = null,
        /** @var FHIRBoolean|null userSelected If this coding was chosen directly by the user */
        public ?FHIRBoolean $userSelected = null,
    ) {
        parent::__construct($id, $extension);
    }
}
