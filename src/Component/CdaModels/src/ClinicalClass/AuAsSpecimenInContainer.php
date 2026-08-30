<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/asSpecimenInContainer',
    name: 'asSpecimenInContainer',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AuAsSpecimenInContainer extends InfrastructureRoot
{
    /**
     * @param list<CS> $realmCode
     * @param list<II> $templateId
     */
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@classCode')]
        public ?string $classCode = null,
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/container',
            propertyKind: 'complex',
            isArray: false,
            isRequired: true,
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public ?AuContainer $container = null,
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
