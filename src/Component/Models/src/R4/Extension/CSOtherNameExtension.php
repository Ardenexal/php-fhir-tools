<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Terminology Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/codesystem-otherName
 * @description Human readable names for the codesystem.  This codesystem has been deprecated.  The codesystem-otherTitle extension should be used for capturing other human readable names.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/codesystem-otherName', fhirVersion: 'R4')]
class CSOtherNameExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var StringPrimitive name Human readable, short and specific */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $name,
		/** @var bool|null preferred Which name is preferred for this language */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
		public ?bool $preferred = null,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'name', value: $this->name);
		if ($this->preferred !== null) {
		    $subExtensions[] = new Extension(url: 'preferred', value: $this->preferred);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/codesystem-otherName',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$name = null;
		$preferred = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'name' && $ext->value instanceof StringPrimitive) {
		        $name = $ext->value;
		    }
		    if ($extUrl === 'preferred' && is_bool($ext->value)) {
		        $preferred = $ext->value;
		    }
		}

		return new static($name, $preferred, $id);
	}
}
