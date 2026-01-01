<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The asserted justification for using the data.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Permission', elementPath: 'Permission.justification', fhirVersion: 'R5')]
class FHIRPermissionJustification extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> basis The regulatory grounds upon which this Permission builds */
		public array $basis = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> evidence Justifing rational */
		public array $evidence = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
