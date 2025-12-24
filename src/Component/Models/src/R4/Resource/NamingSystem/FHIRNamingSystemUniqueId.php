<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates how the system may be identified when referenced in electronic exchange.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NamingSystem', elementPath: 'NamingSystem.uniqueId', fhirVersion: 'R4')]
class FHIRNamingSystemUniqueId extends FHIRBackboneElement
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
        public ?FHIRNamingSystemIdentifierTypeType $type = null,
        /** @var FHIRString|string|null value The unique identifier */
        #[NotBlank]
        public FHIRString|string|null $value = null,
        /** @var FHIRBoolean|null preferred Is this the id that should be used for this type */
        public ?FHIRBoolean $preferred = null,
        /** @var FHIRString|string|null comment Notes about identifier usage */
        public FHIRString|string|null $comment = null,
        /** @var FHIRPeriod|null period When is identifier valid? */
        public ?FHIRPeriod $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
