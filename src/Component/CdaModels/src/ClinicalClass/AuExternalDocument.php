<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\INTType;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-ExternalDocument',
    name: 'au-ExternalDocument',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AuExternalDocument extends ExternalDocument
{
    /**
     * @param list<II>     $id
     * @param list<Author> $sdtcAuthor
     * @param list<CS>     $realmCode
     * @param list<II>     $templateId
     */
    public function __construct(
        ?string $classCode = null,
        string $moodCode = 'EVN',
        array $id = [],
        ?CD $code = null,
        ?ED $text = null,
        ?II $setId = null,
        ?INTType $versionNumber = null,
        array $sdtcAuthor = [],
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            classCode: $classCode,
            moodCode: $moodCode,
            id: $id,
            code: $code,
            text: $text,
            setId: $setId,
            versionNumber: $versionNumber,
            sdtcAuthor: $sdtcAuthor,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
