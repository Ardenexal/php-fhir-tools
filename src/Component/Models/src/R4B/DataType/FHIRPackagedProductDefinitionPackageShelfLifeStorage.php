<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element PackagedProductDefinition.package.shelfLifeStorage
 * @description Shelf Life and storage information.
 */
class FHIRPackagedProductDefinitionPackageShelfLifeStorage extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept type This describes the shelf life, taking into account various scenarios such as shelf life of the packaged Medicinal Product itself, shelf life after transformation where necessary and shelf life after the first opening of a bottle, etc. The shelf life type shall be specified using an appropriate controlled vocabulary The controlled term and the controlled term identifier shall be specified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string periodX The shelf life time period can be specified using a numerical value for the period of time and its unit of time measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $periodX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> specialPrecautionsForStorage Special precautions for storage, if any, can be specified using an appropriate controlled vocabulary. The controlled term and the controlled term identifier shall be specified */
		public array $specialPrecautionsForStorage = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
