<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-complex-type DataRequirement.codeFilter
 * @description Code filters specify additional constraints on the data, specifying the value set of interest for a particular element of the data. Each code filter defines an additional constraint on the data, i.e. code filters are AND'ed, not OR'ed.
 */
class FHIRDataRequirementCodeFilter extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string path A code-valued attribute to filter on */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $path = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string searchParam A coded (token) parameter to search on */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $searchParam = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical valueSet Valueset for the filter */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical $valueSet = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding> code What code is expected */
		public array $code = [],
	) {
		parent::__construct($id, $extension);
	}
}
