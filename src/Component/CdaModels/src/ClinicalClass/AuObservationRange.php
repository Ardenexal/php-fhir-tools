<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLINT;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\ActClassObservation;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-ObservationRange',
    name: 'au-ObservationRange',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AuObservationRange extends ObservationRange
{
    /**
     * @param list<InfrastructureRoot> $sdtcPrecondition1
     * @param list<CS>                 $realmCode
     * @param list<II>                 $templateId
     */
    public function __construct(
        ?ActClassObservation $classCode = null,
        string $moodCode = 'EVN.CRT',
        ?CD $code = null,
        ?ED $text = null,
        ?IVLINT $value = null,
        ?CE $interpretationCode = null,
        array $sdtcPrecondition1 = [],
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            classCode: $classCode,
            moodCode: $moodCode,
            code: $code,
            text: $text,
            value: $value,
            interpretationCode: $interpretationCode,
            sdtcPrecondition1: $sdtcPrecondition1,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
