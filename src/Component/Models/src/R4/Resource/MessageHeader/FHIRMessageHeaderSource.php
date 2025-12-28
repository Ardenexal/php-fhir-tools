<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The source application from which this message originated.
 */
#[FHIRBackboneElement(parentResource: 'MessageHeader', elementPath: 'MessageHeader.source', fhirVersion: 'R4')]
class FHIRMessageHeaderSource extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name Name of system */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null software Name of software running the system */
        public \FHIRString|string|null $software = null,
        /** @var FHIRString|string|null version Version of software running */
        public \FHIRString|string|null $version = null,
        /** @var FHIRContactPoint|null contact Human contact for problems */
        public ?\FHIRContactPoint $contact = null,
        /** @var FHIRUrl|null endpoint Actual message source address or id */
        #[NotBlank]
        public ?\FHIRUrl $endpoint = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
