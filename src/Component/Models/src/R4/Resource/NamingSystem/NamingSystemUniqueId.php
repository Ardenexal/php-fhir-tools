<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\NamingSystem;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\NamingSystemIdentifierTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates how the system may be identified when referenced in electronic exchange.
 */
#[FHIRBackboneElement(parentResource: 'NamingSystem', elementPath: 'NamingSystem.uniqueId', fhirVersion: 'R4')]
class NamingSystemUniqueId extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var NamingSystemIdentifierTypeType|null type oid | uuid | uri | other */
        #[NotBlank]
        public ?NamingSystemIdentifierTypeType $type = null,
        /** @var StringPrimitive|string|null value The unique identifier */
        #[NotBlank]
        public StringPrimitive|string|null $value = null,
        /** @var bool|null preferred Is this the id that should be used for this type */
        public ?bool $preferred = null,
        /** @var StringPrimitive|string|null comment Notes about identifier usage */
        public StringPrimitive|string|null $comment = null,
        /** @var Period|null period When is identifier valid? */
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
