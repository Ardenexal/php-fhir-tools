<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Person;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\IdentityAssuranceLevelType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Link to a resource that concerns the same actual person.
 */
#[FHIRBackboneElement(parentResource: 'Person', elementPath: 'Person.link', fhirVersion: 'R4')]
class PersonLink extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null target The resource to which this actual person is associated */
        #[NotBlank]
        public ?Reference $target = null,
        /** @var IdentityAssuranceLevelType|null assurance level1 | level2 | level3 | level4 */
        public ?IdentityAssuranceLevelType $assurance = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
