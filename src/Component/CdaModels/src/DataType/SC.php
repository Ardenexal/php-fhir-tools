<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/SC',
    name: 'SC',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: [
        'nullFlavor',
        'representation',
        'mediaType',
        'language',
        'xmlText',
        'code',
        'codeSystem',
        'codeSystemName',
        'codeSystemVersion',
        'displayName',
    ],
)]
class SC extends ST
{
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@code')]
        public ?string $code = null,
        #[FhirProperty(fhirType: 'string', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@codeSystem')]
        public ?string $codeSystem = null,
        #[FhirProperty(fhirType: 'string', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@codeSystemName')]
        public ?string $codeSystemName = null,
        #[FhirProperty(fhirType: 'string', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@codeSystemVersion')]
        public ?string $codeSystemVersion = null,
        #[FhirProperty(fhirType: 'string', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@displayName')]
        public ?string $displayName = null,
        string $representation = 'TXT',
        string $mediaType = 'text/plain',
        ?string $language = null,
        ?string $xmlText = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            representation: $representation,
            mediaType: $mediaType,
            language: $language,
            xmlText: $xmlText,
            nullFlavor: $nullFlavor,
        );
    }
}
