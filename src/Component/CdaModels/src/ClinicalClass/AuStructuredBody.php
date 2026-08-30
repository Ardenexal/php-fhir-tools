<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-StructuredBody',
    name: 'au-StructuredBody',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/StructuredBody',
)]
class AuStructuredBody extends StructuredBody
{
    /**
     * @param list<StructuredBodyComponent> $component
     * @param list<CS>                      $realmCode
     * @param list<II>                      $templateId
     */
    public function __construct(
        string $classCode = 'DOCBODY',
        string $moodCode = 'EVN',
        ?CE $confidentialityCode = null,
        ?CS $languageCode = null,
        array $component = [],
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            classCode: $classCode,
            moodCode: $moodCode,
            confidentialityCode: $confidentialityCode,
            languageCode: $languageCode,
            component: $component,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
