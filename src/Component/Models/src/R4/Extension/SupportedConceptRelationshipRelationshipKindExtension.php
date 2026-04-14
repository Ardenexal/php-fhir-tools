<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @see http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-relationshipKind
 * @description Identifies a type of relationship between codes that is supported by this code system version
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-relationshipKind', fhirVersion: 'R4')]
class SupportedConceptRelationshipRelationshipKindExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
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
		    url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-relationshipKind',
		    value: $this->valueCode,
		);
	}
}
