<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;

/**
 * @description A description or definition of which activities are allowed to be done on the data.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Permission', elementPath: 'Permission.rule.activity', fhirVersion: 'R5')]
class FHIRPermissionRuleActivity extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRReference> actor Authorized actor(s) */
        public array $actor = [],
        /** @var array<FHIRCodeableConcept> action Actions controlled by this rule */
        public array $action = [],
        /** @var array<FHIRCodeableConcept> purpose The purpose for which the permission is given */
        public array $purpose = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
