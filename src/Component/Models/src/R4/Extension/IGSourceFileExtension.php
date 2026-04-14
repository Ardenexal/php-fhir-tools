<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/implementationguide-sourceFile
 * @description Identifies files used as part of the the publication process other than resources referenced in definition.resource.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/implementationguide-sourceFile', fhirVersion: 'R4')]
class IGSourceFileExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var Reference file Location on server */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $file,
		/** @var StringPrimitive location Path for publisher */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $location,
		/** @var bool|null keepAsResource Use attachment or resource? */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
		public ?bool $keepAsResource = null,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'file', value: $this->file);
		$subExtensions[] = new Extension(url: 'location', value: $this->location);
		if ($this->keepAsResource !== null) {
		    $subExtensions[] = new Extension(url: 'keepAsResource', value: $this->keepAsResource);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/implementationguide-sourceFile',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$file = null;
		$location = null;
		$keepAsResource = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'file' && $ext->value instanceof Reference) {
		        $file = $ext->value;
		    }
		    if ($extUrl === 'location' && $ext->value instanceof StringPrimitive) {
		        $location = $ext->value;
		    }
		    if ($extUrl === 'keepAsResource' && is_bool($ext->value)) {
		        $keepAsResource = $ext->value;
		    }
		}

		return new static($file, $location, $keepAsResource, $id);
	}
}
