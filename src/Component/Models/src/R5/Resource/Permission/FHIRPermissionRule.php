<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRConsentProvisionTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;

/**
 * @description A set of rules.
 */
#[FHIRBackboneElement(parentResource: 'Permission', elementPath: 'Permission.rule', fhirVersion: 'R5')]
class FHIRPermissionRule extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRConsentProvisionTypeType|null type deny | permit */
        public ?FHIRConsentProvisionTypeType $type = null,
        /** @var array<FHIRPermissionRuleData> data The selection criteria to identify data that is within scope of this provision */
        public array $data = [],
        /** @var array<FHIRPermissionRuleActivity> activity A description or definition of which activities are allowed to be done on the data */
        public array $activity = [],
        /** @var array<FHIRCodeableConcept> limit What limits apply to the use of the data */
        public array $limit = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
