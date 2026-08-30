<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/CO',
    name: 'CO',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class CO extends CV
{
    /**
     * @param list<CR> $qualifier
     * @param list<CD> $translation
     */
    public function __construct(
        ?string $code = null,
        ?string $codeSystem = null,
        ?string $codeSystemName = null,
        ?string $codeSystemVersion = null,
        ?string $displayName = null,
        ?string $sdtcValueSet = null,
        ?string $sdtcValueSetVersion = null,
        ?ED $originalText = null,
        array $qualifier = [],
        array $translation = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            code: $code,
            codeSystem: $codeSystem,
            codeSystemName: $codeSystemName,
            codeSystemVersion: $codeSystemVersion,
            displayName: $displayName,
            sdtcValueSet: $sdtcValueSet,
            sdtcValueSetVersion: $sdtcValueSetVersion,
            originalText: $originalText,
            qualifier: $qualifier,
            translation: $translation,
            nullFlavor: $nullFlavor,
        );
    }
}
