<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element Composition.attester
 * @description A participant who has attested to the accuracy of the composition/document.
 */
class FHIRCompositionAttester extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCompositionAttestationModeType mode personal | professional | legal | official */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCompositionAttestationModeType $mode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime time When the composition was attested */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime $time = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference party Who attested the composition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $party = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
