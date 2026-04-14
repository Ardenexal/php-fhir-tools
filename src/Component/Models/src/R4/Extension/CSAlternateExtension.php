<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Terminology Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/codesystem-alternate
 * @description An additional code that may be used to represent the concept.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/codesystem-alternate', fhirVersion: 'R4')]
class CSAlternateExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CodePrimitive code Code that represents the concept */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $code,
		/** @var Coding use Expected use of the code */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $use,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'code', value: $this->code);
		$subExtensions[] = new Extension(url: 'use', value: $this->use);
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/codesystem-alternate',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$code = null;
		$use = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'code' && $ext->value instanceof CodePrimitive) {
		        $code = $ext->value;
		    }
		    if ($extUrl === 'use' && $ext->value instanceof Coding) {
		        $use = $ext->value;
		    }
		}

		return new static($code, $use, $id);
	}
}
