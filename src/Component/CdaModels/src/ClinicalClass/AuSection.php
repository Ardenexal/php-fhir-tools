<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ST;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Section',
    name: 'au-Section',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/Section',
)]
class AuSection extends Section
{
    /**
     * @param list<AuCoverage2>      $coverage2
     * @param list<Author>           $author
     * @param list<Informant>        $informant
     * @param list<Entry>            $entry
     * @param list<SectionComponent> $component
     * @param list<CS>               $realmCode
     * @param list<II>               $templateId
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/coverage2',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuCoverage2',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $coverage2 = [],
        ?string $ID = null,
        string $classCode = 'DOCSECT',
        string $moodCode = 'EVN',
        ?II $id = null,
        ?CE $code = null,
        ?ST $title = null,
        ?string $text = null,
        ?CE $confidentialityCode = null,
        ?CS $languageCode = null,
        ?Subject $subject = null,
        array $author = [],
        array $informant = [],
        array $entry = [],
        array $component = [],
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            ID: $ID,
            classCode: $classCode,
            moodCode: $moodCode,
            id: $id,
            code: $code,
            title: $title,
            text: $text,
            confidentialityCode: $confidentialityCode,
            languageCode: $languageCode,
            subject: $subject,
            author: $author,
            informant: $informant,
            entry: $entry,
            component: $component,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
