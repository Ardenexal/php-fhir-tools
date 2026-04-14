<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/capabilitystatement-search-parameter-use
 * @description This extension defines if a search parameter is only allowed in certain contexts
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-search-parameter-use', fhirVersion: 'R4')]
class CSSearchParameterUseExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var bool required If this search parameter can use used in standalone search. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
		public bool $required,
		/** @var bool allowInclude If this search parameter can use used in _include search. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
		public bool $allowInclude,
		/** @var bool allowRevinclude If this search parameter can use used in _revinclude search. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
		public bool $allowRevinclude,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'required', value: $this->required);
		$subExtensions[] = new Extension(url: 'allow-include', value: $this->allowInclude);
		$subExtensions[] = new Extension(url: 'allow-revinclude', value: $this->allowRevinclude);
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-search-parameter-use',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$required = null;
		$allowInclude = null;
		$allowRevinclude = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'required' && is_bool($ext->value)) {
		        $required = $ext->value;
		    }
		    if ($extUrl === 'allow-include' && is_bool($ext->value)) {
		        $allowInclude = $ext->value;
		    }
		    if ($extUrl === 'allow-revinclude' && is_bool($ext->value)) {
		        $allowRevinclude = $ext->value;
		    }
		}

		return new static($required, $allowInclude, $allowRevinclude, $id);
	}
}
