<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\DeviceDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\DeviceCorrectiveActionScopeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Tracking of latest field safety corrective action.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.correctiveAction', fhirVersion: 'R5')]
class DeviceDefinitionCorrectiveAction extends BackboneElement
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
        /** @var bool|null recall Whether the corrective action was a recall */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isRequired: true), NotBlank]
        public ?bool $recall = null,
        /** @var DeviceCorrectiveActionScopeType|null scope model | lot-numbers | serial-numbers */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?DeviceCorrectiveActionScopeType $scope = null,
        /** @var Period|null period Start and end dates of the  corrective action */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
