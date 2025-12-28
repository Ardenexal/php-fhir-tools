<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Signature
 *
 * @description A signature along with supporting context. The signature may be a digital signature that is cryptographic in nature, or some other signature acceptable to the domain. This other signature may be as simple as a graphical image representing a hand-written signature, or a signature ceremony Different signature approaches have different utilities.
 */
#[FHIRComplexType(typeName: 'Signature', fhirVersion: 'R4B')]
class FHIRSignature extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRCoding> type Indication of the reason the entity signed the object(s) */
        public array $type = [],
        /** @var FHIRInstant|null when When the signature was created */
        #[NotBlank]
        public ?\FHIRInstant $when = null,
        /** @var FHIRReference|null who Who signed */
        #[NotBlank]
        public ?\FHIRReference $who = null,
        /** @var FHIRReference|null onBehalfOf The party represented */
        public ?\FHIRReference $onBehalfOf = null,
        /** @var FHIRMimeTypesType|null targetFormat The technical format of the signed resources */
        public ?\FHIRMimeTypesType $targetFormat = null,
        /** @var FHIRMimeTypesType|null sigFormat The technical format of the signature */
        public ?\FHIRMimeTypesType $sigFormat = null,
        /** @var FHIRBase64Binary|null data The actual signature content (XML DigSig. JWS, picture, etc.) */
        public ?\FHIRBase64Binary $data = null,
    ) {
        parent::__construct($id, $extension);
    }
}
