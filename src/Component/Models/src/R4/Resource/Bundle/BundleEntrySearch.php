<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchEntryModeType;

/**
 * @description Information about the search process that lead to the creation of this entry.
 */
#[FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry.search', fhirVersion: 'R4')]
class BundleEntrySearch extends BackboneElement
{
    public const FHIR_PROPERTY_MAP = [
        'id' => [
            'fhirType'          => 'http://hl7.org/fhirpath/System.String',
            'propertyKind'      => 'scalar',
            'isArray'           => false,
            'isRequired'        => false,
            'isChoice'          => false,
            'jsonKey'           => null,
            'phpType'           => null,
            'variants'          => null,
            'xmlSerializedName' => '@id',
        ],
        'extension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'extension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'modifierExtension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'modifierExtension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'mode' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'score' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
    ];

    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var SearchEntryModeType|null mode match | include | outcome - why this is in the result set */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?SearchEntryModeType $mode = null,
        /** @var numeric-string|null score Search ranking (between 0 and 1) */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $score = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
