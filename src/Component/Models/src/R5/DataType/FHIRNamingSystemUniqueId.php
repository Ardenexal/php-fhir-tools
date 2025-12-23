<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element NamingSystem.uniqueId
 * @description Indicates how the system may be identified when referenced in electronic exchange.
 */
class FHIRNamingSystemUniqueId extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNamingSystemIdentifierTypeType type oid | uuid | uri | iri-stem | v2csmnemonic | other */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNamingSystemIdentifierTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string value The unique identifier */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean preferred Is this the id that should be used for this type */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $preferred = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string comment Notes about identifier usage */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $comment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod period When is identifier valid? */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean authoritative Whether the identifier is authoritative */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $authoritative = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
