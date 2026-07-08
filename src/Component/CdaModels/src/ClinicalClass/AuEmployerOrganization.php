<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\AuOrganizationName;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/employerOrganization',
    name: 'employerOrganization',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AuEmployerOrganization extends InfrastructureRoot
{
    /**
     * @param list<II> $id
     * @param list<CS> $realmCode
     * @param list<II> $templateId
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/II',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\II',
        )]
        public array $id = [],
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-OrganizationName',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?AuOrganizationName $name = null,
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-OrganizationPartOf',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?AuOrganizationPartOf $asOrganizationPartOf = null,
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
