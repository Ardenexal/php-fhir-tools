<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Tagged value pairs for conveying additional information about the entity.
 */
#[FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.entity.detail', fhirVersion: 'R4B')]
class FHIRAuditEventEntityDetail extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null type Name of the property */
        #[NotBlank]
        public \FHIRString|string|null $type = null,
        /** @var FHIRString|string|FHIRBase64Binary|null valueX Property value */
        #[NotBlank]
        public \FHIRString|string|\FHIRBase64Binary|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
