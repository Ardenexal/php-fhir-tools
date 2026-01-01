<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Information about the search process that lead to the creation of this entry.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry.search', fhirVersion: 'R5')]
class FHIRBundleEntrySearch extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSearchEntryModeType mode match | include - why this is in the result set */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSearchEntryModeType $mode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal score Search ranking (between 0 and 1) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $score = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
