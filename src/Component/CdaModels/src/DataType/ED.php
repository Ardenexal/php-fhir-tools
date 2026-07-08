<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\BinaryDataEncoding;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\CompressionAlgorithm;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/ED',
    name: 'ED',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
#[FHIRPathInvariant(
    key: 'ed-base64',
    severity: 'error',
    expression: '(representation.empty() or representation != \'B64\') or xmlText.empty() or xmlText.matches(\'^(?:[A-Za-z0-9+//]{4})*(?:[A-Za-z0-9+//]{2}==|[A-Za-z0-9+//]{3}=)?$\')',
    human: 'If @representation=\'B64\', then xmlText SHALL be a base64binary string.',
)]
class ED extends ANY
{
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'enum', isArray: false, isRequired: false, xmlSerializedName: '@compression')]
        public ?CompressionAlgorithm $compression = null,
        #[FhirProperty(
            fhirType: 'base64Binary',
            propertyKind: 'scalar',
            isArray: false,
            isRequired: false,
            xmlSerializedName: '@integrityCheck',
        )]
        public ?string $integrityCheck = null,
        #[FhirProperty(
            fhirType: 'code',
            propertyKind: 'scalar',
            isArray: false,
            isRequired: false,
            xmlSerializedName: '@integrityCheckAlgorithm',
        )]
        public ?string $integrityCheckAlgorithm = null,
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@language')]
        public ?string $language = null,
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@mediaType')]
        public ?string $mediaType = null,
        #[FhirProperty(fhirType: 'code', propertyKind: 'enum', isArray: false, isRequired: false, xmlSerializedName: '@representation')]
        public ?BinaryDataEncoding $representation = null,
        #[FhirProperty(fhirType: 'string', propertyKind: 'scalar', isArray: false, isRequired: false)]
        public ?string $xmlText = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/TEL',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?TEL $reference = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/ED',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?ED $thumbnail = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            nullFlavor: $nullFlavor,
        );
    }
}
