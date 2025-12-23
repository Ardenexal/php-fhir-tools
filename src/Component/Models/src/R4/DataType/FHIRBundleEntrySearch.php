<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element Bundle.entry.search
 * @description Information about the search process that lead to the creation of this entry.
 */
class FHIRBundleEntrySearch extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSearchEntryModeType mode match | include | outcome - why this is in the result set */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSearchEntryModeType $mode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal score Search ranking (between 0 and 1) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $score = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
