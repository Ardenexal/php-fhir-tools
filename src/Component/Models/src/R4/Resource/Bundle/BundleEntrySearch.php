<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle;

/**
 * @description Information about the search process that lead to the creation of this entry.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry.search', fhirVersion: 'R4')]
class BundleEntrySearch extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchEntryModeType mode match | include | outcome - why this is in the result set */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchEntryModeType $mode = null,
		/** @var null|float score Search ranking (between 0 and 1) */
		public ?float $score = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
