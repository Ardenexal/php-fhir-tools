<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description When using an account for billing a specific Encounter the set of diagnoses that are relevant for billing are stored here on the account where they are able to be sequenced appropriately prior to processing to produce claim(s).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Account', elementPath: 'Account.diagnosis', fhirVersion: 'R5')]
class FHIRAccountDiagnosis extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt sequence Ranking of the diagnosis (for each type) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt $sequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference condition The diagnosis relevant to the account */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference $condition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime dateOfDiagnosis Date of the diagnosis (when coded diagnosis) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $dateOfDiagnosis = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> type Type that this diagnosis has relevant to the account (e.g. admission, billing, discharge …) */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean onAdmission Diagnosis present on Admission */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $onAdmission = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> packageCode Package Code specific for billing */
		public array $packageCode = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
