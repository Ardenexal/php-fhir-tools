<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Specimen conditioned in a container as expected by the testing laboratory.
 */
#[FHIRBackboneElement(parentResource: 'SpecimenDefinition', elementPath: 'SpecimenDefinition.typeTested', fhirVersion: 'R4B')]
class FHIRSpecimenDefinitionTypeTested extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|null isDerived Primary or secondary specimen */
        public ?\FHIRBoolean $isDerived = null,
        /** @var FHIRCodeableConcept|null type Type of intended specimen */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRSpecimenContainedPreferenceType|null preference preferred | alternate */
        #[NotBlank]
        public ?\FHIRSpecimenContainedPreferenceType $preference = null,
        /** @var FHIRSpecimenDefinitionTypeTestedContainer|null container The specimen's container */
        public ?\FHIRSpecimenDefinitionTypeTestedContainer $container = null,
        /** @var FHIRString|string|null requirement Specimen requirements */
        public \FHIRString|string|null $requirement = null,
        /** @var FHIRDuration|null retentionTime Specimen retention time */
        public ?\FHIRDuration $retentionTime = null,
        /** @var array<FHIRCodeableConcept> rejectionCriterion Rejection criterion */
        public array $rejectionCriterion = [],
        /** @var array<FHIRSpecimenDefinitionTypeTestedHandling> handling Specimen handling before testing */
        public array $handling = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
