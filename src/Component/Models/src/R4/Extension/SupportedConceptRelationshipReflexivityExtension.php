<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @see http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-reflexivity
 * @description Indicates if the association always holds for a concept with itself (refexive), never holds for a concept with itself (irreflexive)
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-reflexivity', fhirVersion: 'R4')]
class SupportedConceptRelationshipReflexivityExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var CodePrimitive|null valueCode Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $valueCode = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-reflexivity',
		    value: $this->valueCode,
		);
	}
}
