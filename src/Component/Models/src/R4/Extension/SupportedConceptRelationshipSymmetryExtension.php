<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @see http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-symmetry
 * @description Indicates if the relationship always holds in the reverse direction as well (symetric), never holds in the reverse direction as well (antisymetric)
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-symmetry', fhirVersion: 'R4')]
class SupportedConceptRelationshipSymmetryExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
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
		    url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-symmetry',
		    value: $this->valueCode,
		);
	}
}
