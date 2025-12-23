<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element SpecimenDefinition.typeTested
 * @description Specimen conditioned in a container as expected by the testing laboratory.
 */
class FHIRSpecimenDefinitionTypeTested extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean isDerived Primary or secondary specimen */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean $isDerived = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type Type of intended specimen */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSpecimenContainedPreferenceType preference preferred | alternate */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSpecimenContainedPreferenceType $preference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSpecimenDefinitionTypeTestedContainer container The specimen's container */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSpecimenDefinitionTypeTestedContainer $container = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string requirement Specimen requirements */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $requirement = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDuration retentionTime Specimen retention time */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDuration $retentionTime = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> rejectionCriterion Rejection criterion */
		public array $rejectionCriterion = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSpecimenDefinitionTypeTestedHandling> handling Specimen handling before testing */
		public array $handling = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
