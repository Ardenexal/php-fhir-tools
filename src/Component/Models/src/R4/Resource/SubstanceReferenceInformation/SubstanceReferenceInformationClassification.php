<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceReferenceInformation;

/**
 * @description Todo.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'SubstanceReferenceInformation',
	elementPath: 'SubstanceReferenceInformation.classification',
	fhirVersion: 'R4',
)]
class SubstanceReferenceInformationClassification extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept domain Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $domain = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept classification Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $classification = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> subtype Todo */
		public array $subtype = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> source Todo */
		public array $source = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
