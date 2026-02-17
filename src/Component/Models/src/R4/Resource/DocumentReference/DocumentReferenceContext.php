<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DocumentReference;

/**
 * @description The clinical context in which the document was prepared.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'DocumentReference', elementPath: 'DocumentReference.context', fhirVersion: 'R4')]
class DocumentReferenceContext extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> encounter Context of the document  content */
		public array $encounter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> event Main clinical acts documented */
		public array $event = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period Time of service that is being documented */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept facilityType Kind of facility where patient was seen */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $facilityType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept practiceSetting Additional details about where the content was created (e.g. clinical specialty) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $practiceSetting = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference sourcePatientInfo Patient demographics from source */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $sourcePatientInfo = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> related Related identifiers or resources */
		public array $related = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
