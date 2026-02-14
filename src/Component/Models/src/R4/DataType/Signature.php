<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Signature
 *
 * @description A signature along with supporting context. The signature may be a digital signature that is cryptographic in nature, or some other signature acceptable to the domain. This other signature may be as simple as a graphical image representing a hand-written signature, or a signature ceremony Different signature approaches have different utilities.
 */
#[FHIRComplexType(typeName: 'Signature', fhirVersion: 'R4')]
class Signature extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Coding> type Indication of the reason the entity signed the object(s) */
        public array $type = [],
        /** @var InstantPrimitive|null when When the signature was created */
        #[NotBlank]
        public ?InstantPrimitive $when = null,
        /** @var Reference|null who Who signed */
        #[NotBlank]
        public ?Reference $who = null,
        /** @var Reference|null onBehalfOf The party represented */
        public ?Reference $onBehalfOf = null,
        /** @var MimeTypesType|null targetFormat The technical format of the signed resources */
        public ?MimeTypesType $targetFormat = null,
        /** @var MimeTypesType|null sigFormat The technical format of the signature */
        public ?MimeTypesType $sigFormat = null,
        /** @var Base64BinaryPrimitive|null data The actual signature content (XML DigSig. JWS, picture, etc.) */
        public ?Base64BinaryPrimitive $data = null,
    ) {
        parent::__construct($id, $extension);
    }
}
