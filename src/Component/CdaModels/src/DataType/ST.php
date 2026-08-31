<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/ST',
    name: 'ST',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: ['nullFlavor', 'representation', 'mediaType', 'language', 'xmlText'],
)]
#[FHIRPathInvariant(
    key: 'text-null',
    severity: 'error',
    expression: '(xmlText | nullFlavor).count() = 1',
    human: 'xmlText and nullFlavor are mutually exclusive (one must be present)',
)]
class ST extends ANY
{
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@representation')]
        public string $representation = 'TXT',
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@mediaType')]
        public string $mediaType = 'text/plain',
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@language')]
        public ?string $language = null,
        #[FhirProperty(fhirType: 'string', propertyKind: 'scalar', isArray: false, isRequired: false)]
        public ?string $xmlText = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            nullFlavor: $nullFlavor,
        );
    }
}
