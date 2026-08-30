<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Component',
    name: 'au-Component',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/Component',
)]
class AuComponent extends Component
{
    /**
     * @param list<CS> $realmCode
     * @param list<II> $templateId
     */
    public function __construct(
        string $typeCode = 'COMP',
        bool $contextConductionInd = true,
        ?NonXMLBody $nonXMLBody = null,
        ?StructuredBody $structuredBody = null,
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            typeCode: $typeCode,
            contextConductionInd: $contextConductionInd,
            nonXMLBody: $nonXMLBody,
            structuredBody: $structuredBody,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
