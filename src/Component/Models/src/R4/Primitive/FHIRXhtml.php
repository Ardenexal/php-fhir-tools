<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Primitive;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/xhtml
 * @description XHTML
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive(primitiveType: 'xhtml', fhirVersion: 'R4')]
class FHIRXhtml extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id xml:id (or equivalent in JSON) */
		public ?string $id = null,
		/** @var null|string value Actual xhtml */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?string $value = null,
	) {
		parent::__construct($id);
	}
}
