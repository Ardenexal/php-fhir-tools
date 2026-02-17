<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SpecimenDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SpecimenContainedPreferenceType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Specimen conditioned in a container as expected by the testing laboratory.
 */
#[FHIRBackboneElement(parentResource: 'SpecimenDefinition', elementPath: 'SpecimenDefinition.typeTested', fhirVersion: 'R4')]
class SpecimenDefinitionTypeTested extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var bool|null isDerived Primary or secondary specimen */
        public ?bool $isDerived = null,
        /** @var CodeableConcept|null type Type of intended specimen */
        public ?CodeableConcept $type = null,
        /** @var SpecimenContainedPreferenceType|null preference preferred | alternate */
        #[NotBlank]
        public ?SpecimenContainedPreferenceType $preference = null,
        /** @var SpecimenDefinitionTypeTestedContainer|null container The specimen's container */
        public ?SpecimenDefinitionTypeTestedContainer $container = null,
        /** @var StringPrimitive|string|null requirement Specimen requirements */
        public StringPrimitive|string|null $requirement = null,
        /** @var Duration|null retentionTime Specimen retention time */
        public ?Duration $retentionTime = null,
        /** @var array<CodeableConcept> rejectionCriterion Rejection criterion */
        public array $rejectionCriterion = [],
        /** @var array<SpecimenDefinitionTypeTestedHandling> handling Specimen handling before testing */
        public array $handling = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
