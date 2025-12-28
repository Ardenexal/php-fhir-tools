<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates how the system may be identified when referenced in electronic exchange.
 */
#[FHIRBackboneElement(parentResource: 'NamingSystem', elementPath: 'NamingSystem.uniqueId', fhirVersion: 'R4B')]
class FHIRNamingSystemUniqueId extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRNamingSystemIdentifierTypeType|null type oid | uuid | uri | other */
        #[NotBlank]
        public ?\FHIRNamingSystemIdentifierTypeType $type = null,
        /** @var FHIRString|string|null value The unique identifier */
        #[NotBlank]
        public \FHIRString|string|null $value = null,
        /** @var FHIRBoolean|null preferred Is this the id that should be used for this type */
        public ?\FHIRBoolean $preferred = null,
        /** @var FHIRString|string|null comment Notes about identifier usage */
        public \FHIRString|string|null $comment = null,
        /** @var FHIRPeriod|null period When is identifier valid? */
        public ?\FHIRPeriod $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
