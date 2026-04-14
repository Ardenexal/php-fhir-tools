<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @see http://terminology.hl7.org/StructureDefinition/ext-namingsystem-version
 * @description The business version associated with the Naming System
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://terminology.hl7.org/StructureDefinition/ext-namingsystem-version', fhirVersion: 'R4')]
class NamingSystemVersionExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var StringPrimitive|null valueString Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $valueString = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://terminology.hl7.org/StructureDefinition/ext-namingsystem-version',
		    value: $this->valueString,
		);
	}
}
