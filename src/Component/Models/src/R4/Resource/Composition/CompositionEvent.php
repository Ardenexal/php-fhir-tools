<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Composition;

/**
 * @description The clinical service, such as a colonoscopy or an appendectomy, being documented.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Composition', elementPath: 'Composition.event', fhirVersion: 'R4')]
class CompositionEvent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> code Code(s) that apply to the event being documented */
		public array $code = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period The period covered by the documentation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> detail The event(s) being documented */
		public array $detail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
