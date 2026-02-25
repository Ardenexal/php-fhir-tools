<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/VirtualServiceDetail
 *
 * @description Virtual Service Contact Details.
 */
#[FHIRComplexType(typeName: 'VirtualServiceDetail', fhirVersion: 'R5')]
class VirtualServiceDetail extends DataType
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
        'channelType' => [
            'fhirType'     => 'Coding',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'address' => [
            'fhirType'     => 'choice',
            'propertyKind' => 'choice',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => true,
            'jsonKey'      => null,
            'variants'     => [
                [
                    'fhirType'     => 'url',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive',
                    'jsonKey'      => 'addressUrl',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
                    'jsonKey'      => 'addressString',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'ContactPoint',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactPoint',
                    'jsonKey'      => 'addressContactPoint',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'ExtendedContactDetail',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ExtendedContactDetail',
                    'jsonKey'      => 'addressExtendedContactDetail',
                    'isBuiltin'    => false,
                ],
            ],
        ],
        'additionalInfo' => [
            'fhirType'     => 'url',
            'propertyKind' => 'primitive',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'maxParticipants' => [
            'fhirType'     => 'positiveInt',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'sessionKey' => [
            'fhirType'     => 'string',
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
        /** @var Coding|null channelType Channel Type */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public ?Coding $channelType = null,
        /** @var UrlPrimitive|StringPrimitive|string|ContactPoint|ExtendedContactDetail|null address Contact address/number */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'url',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive',
                    'jsonKey'      => 'addressUrl',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
                    'jsonKey'      => 'addressString',
                ],
                [
                    'fhirType'     => 'ContactPoint',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactPoint',
                    'jsonKey'      => 'addressContactPoint',
                ],
                [
                    'fhirType'     => 'ExtendedContactDetail',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ExtendedContactDetail',
                    'jsonKey'      => 'addressExtendedContactDetail',
                ],
            ],
        )]
        public UrlPrimitive|StringPrimitive|string|ContactPoint|ExtendedContactDetail|null $address = null,
        /** @var array<UrlPrimitive> additionalInfo Address to see alternative connection details */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive', isArray: true)]
        public array $additionalInfo = [],
        /** @var PositiveIntPrimitive|null maxParticipants Maximum number of participants supported by the virtual service */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $maxParticipants = null,
        /** @var StringPrimitive|string|null sessionKey Session Key required by the virtual service */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $sessionKey = null,
    ) {
        parent::__construct($id, $extension);
    }
}
