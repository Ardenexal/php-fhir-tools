<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Specimen conditioned in a container as expected by the testing laboratory.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SpecimenDefinition', elementPath: 'SpecimenDefinition.typeTested', fhirVersion: 'R5')]
class FHIRSpecimenDefinitionTypeTested extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean isDerived Primary or secondary specimen */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $isDerived = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept type Type of intended specimen */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSpecimenContainedPreferenceType preference preferred | alternate */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSpecimenContainedPreferenceType $preference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSpecimenDefinitionTypeTestedContainer container The specimen's container */
		public ?FHIRSpecimenDefinitionTypeTestedContainer $container = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown requirement Requirements for specimen delivery and special handling */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $requirement = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration retentionTime The usual time for retaining this kind of specimen */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration $retentionTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean singleUse Specimen for single use only */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $singleUse = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> rejectionCriterion Criterion specified for specimen rejection */
		public array $rejectionCriterion = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSpecimenDefinitionTypeTestedHandling> handling Specimen handling before testing */
		public array $handling = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> testingDestination Where the specimen will be tested */
		public array $testingDestination = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
