<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Link to a resource that concerns the same actual person.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Person', elementPath: 'Person.link', fhirVersion: 'R4B')]
class FHIRPersonLink extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null target The resource to which this actual person is associated */
        #[NotBlank]
        public ?FHIRReference $target = null,
        /** @var FHIRIdentityAssuranceLevelType|null assurance level1 | level2 | level3 | level4 */
        public ?FHIRIdentityAssuranceLevelType $assurance = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
