<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element ImplementationGuide.dependsOn
 * @description Another implementation guide that this implementation depends on. Typically, an implementation guide uses value sets, profiles etc.defined in other implementation guides.
 */
class FHIRImplementationGuideDependsOn extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical uri Identity of the IG that this depends on */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical $uri = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRId packageId NPM Package name for IG this depends on */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRId $packageId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string version Version of the IG */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $version = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
