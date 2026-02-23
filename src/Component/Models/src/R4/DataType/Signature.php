<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
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
    public const FHIR_PROPERTY_MAP = [
        'id' => [
            'fhirType'     => 'http://hl7.org/fhirpath/System.String',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'extension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'extension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'type' => [
            'fhirType'     => 'Coding',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'when' => [
            'fhirType'     => 'instant',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'who' => [
            'fhirType'     => 'Reference',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'onBehalfOf' => [
            'fhirType'     => 'Reference',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'targetFormat' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'sigFormat' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'data' => [
            'fhirType'     => 'base64Binary',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
    ];

    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Coding> type Indication of the reason the entity signed the object(s) */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex', isArray: true, isRequired: true)]
        public array $type = [],
        /** @var InstantPrimitive|null when When the signature was created */
        #[FhirProperty(fhirType: 'instant', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?InstantPrimitive $when = null,
        /** @var Reference|null who Who signed */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $who = null,
        /** @var Reference|null onBehalfOf The party represented */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $onBehalfOf = null,
        /** @var MimeTypesType|null targetFormat The technical format of the signed resources */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?MimeTypesType $targetFormat = null,
        /** @var MimeTypesType|null sigFormat The technical format of the signature */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?MimeTypesType $sigFormat = null,
        /** @var Base64BinaryPrimitive|null data The actual signature content (XML DigSig. JWS, picture, etc.) */
        #[FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive')]
        public ?Base64BinaryPrimitive $data = null,
    ) {
        parent::__construct($id, $extension);
    }
}
