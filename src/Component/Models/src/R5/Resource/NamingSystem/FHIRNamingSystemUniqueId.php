<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Indicates how the system may be identified when referenced in electronic exchange.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NamingSystem', elementPath: 'NamingSystem.uniqueId', fhirVersion: 'R5')]
class FHIRNamingSystemUniqueId extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNamingSystemIdentifierTypeType type oid | uuid | uri | iri-stem | v2csmnemonic | other */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNamingSystemIdentifierTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string value The unique identifier */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean preferred Is this the id that should be used for this type */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $preferred = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string comment Notes about identifier usage */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $comment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod period When is identifier valid? */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean authoritative Whether the identifier is authoritative */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $authoritative = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
