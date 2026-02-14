<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageHeader;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The source application from which this message originated.
 */
#[FHIRBackboneElement(parentResource: 'MessageHeader', elementPath: 'MessageHeader.source', fhirVersion: 'R4')]
class MessageHeaderSource extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null name Name of system */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null software Name of software running the system */
        public StringPrimitive|string|null $software = null,
        /** @var StringPrimitive|string|null version Version of software running */
        public StringPrimitive|string|null $version = null,
        /** @var ContactPoint|null contact Human contact for problems */
        public ?ContactPoint $contact = null,
        /** @var UrlPrimitive|null endpoint Actual message source address or id */
        #[NotBlank]
        public ?UrlPrimitive $endpoint = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
