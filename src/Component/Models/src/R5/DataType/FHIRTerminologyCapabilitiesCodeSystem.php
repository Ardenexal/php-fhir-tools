<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element TerminologyCapabilities.codeSystem
 * @description Identifies a code system that is supported by the server. If there is a no code system URL, then this declares the general assumptions a client can make about support for any CodeSystem resource.
 */
class FHIRTerminologyCapabilitiesCodeSystem extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical uri Canonical identifier for the code system, represented as a URI */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $uri = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTerminologyCapabilitiesCodeSystemVersion> version Version of Code System supported */
		public array $version = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeSystemContentModeType content not-present | example | fragment | complete | supplement */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeSystemContentModeType $content = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean subsumption Whether subsumption is supported */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $subsumption = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
