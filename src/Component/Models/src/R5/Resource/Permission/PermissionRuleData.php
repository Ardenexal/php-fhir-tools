<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Permission;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;

/**
 * @description A description or definition of which activities are allowed to be done on the data.
 */
#[FHIRBackboneElement(parentResource: 'Permission', elementPath: 'Permission.rule.data', fhirVersion: 'R5')]
class PermissionRuleData extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<PermissionRuleDataResource> resource Explicit FHIR Resource references */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Permission\PermissionRuleDataResource',
        )]
        public array $resource = [],
        /** @var array<Coding> security Security tag code on .meta.security */
        #[FhirProperty(
            fhirType: 'Coding',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
        )]
        public array $security = [],
        /** @var array<Period> period Timeframe encompasing data create/update */
        #[FhirProperty(
            fhirType: 'Period',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Period',
        )]
        public array $period = [],
        /** @var Expression|null expression Expression identifying the data */
        #[FhirProperty(fhirType: 'Expression', propertyKind: 'complex')]
        public ?Expression $expression = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
