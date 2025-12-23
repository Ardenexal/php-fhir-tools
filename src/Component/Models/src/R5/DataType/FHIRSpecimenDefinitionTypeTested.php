<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element SpecimenDefinition.typeTested
 * @description Specimen conditioned in a container as expected by the testing laboratory.
 */
class FHIRSpecimenDefinitionTypeTested extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean isDerived Primary or secondary specimen */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $isDerived = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Type of intended specimen */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSpecimenContainedPreferenceType preference preferred | alternate */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSpecimenContainedPreferenceType $preference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSpecimenDefinitionTypeTestedContainer container The specimen's container */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSpecimenDefinitionTypeTestedContainer $container = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown requirement Requirements for specimen delivery and special handling */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $requirement = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration retentionTime The usual time for retaining this kind of specimen */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration $retentionTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean singleUse Specimen for single use only */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $singleUse = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> rejectionCriterion Criterion specified for specimen rejection */
		public array $rejectionCriterion = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSpecimenDefinitionTypeTestedHandling> handling Specimen handling before testing */
		public array $handling = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> testingDestination Where the specimen will be tested */
		public array $testingDestination = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
