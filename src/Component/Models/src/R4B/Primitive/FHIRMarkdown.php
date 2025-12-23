<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Primitive;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/markdown
 * @description A string that may contain Github Flavored Markdown syntax for optional processing by a mark down presentation engine
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive(primitiveType: 'markdown', fhirVersion: 'R4B')]
class FHIRMarkdown extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString
{
	public function __construct(
		/** @var null|string id xml:id (or equivalent in JSON) */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|string value Primitive value for markdown */
		public ?string $value = null,
	) {
		parent::__construct($id, $extension, $value);
	}
}
