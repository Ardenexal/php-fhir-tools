<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/id',
    name: 'id',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: [
        'nullFlavor',
        'realmCode',
        'typeId',
        'templateId',
        'assigningAuthorityName',
        'displayable',
        'root',
        'extension',
    ],
)]
#[FHIRPathInvariant(
    key: 'II-1',
    severity: 'error',
    expression: 'root.exists() or nullFlavor.exists()',
    human: 'An II instance must have either a root or an nullFlavor.',
)]
class AuId extends InfrastructureRoot
{
    /**
     * @param list<CS> $realmCode
     * @param list<II> $templateId
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'string',
            propertyKind: 'scalar',
            isArray: false,
            isRequired: false,
            xmlSerializedName: '@assigningAuthorityName',
        )]
        public ?string $assigningAuthorityName = null,
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@displayable')]
        public ?bool $displayable = null,
        #[FhirProperty(fhirType: 'string', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@root')]
        public ?string $root = null,
        #[FhirProperty(fhirType: 'string', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@extension')]
        public ?string $extension = null,
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
