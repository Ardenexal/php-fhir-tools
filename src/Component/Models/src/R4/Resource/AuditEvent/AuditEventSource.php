<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The system that is reporting the event.
 */
#[FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.source', fhirVersion: 'R4')]
class AuditEventSource extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null site Logical source location within the enterprise */
        public StringPrimitive|string|null $site = null,
        /** @var Reference|null observer The identity of source detecting the event */
        #[NotBlank]
        public ?Reference $observer = null,
        /** @var array<Coding> type The type of source where event originated */
        public array $type = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
