<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Orders and Observations
 * @see http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-manipulation
 * @description Extension for manipulation of a biologically dervied product.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-manipulation', fhirVersion: 'R4')]
class BDPManipulationExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var StringPrimitive|null description Description of manipulation */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $description = null,
		/** @var array<CodeableReference> procedure Procesing procedure */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex', isArray: true)]
		public array $procedure = [],
		/** @var DateTimePrimitive|null timeX Time of manipulation */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $timeX = null,
		?string $id = null,
	) {
		$subExtensions = [];
		if ($this->description !== null) {
		    $subExtensions[] = new Extension(url: 'description', value: $this->description);
		}
		foreach ($this->procedure as $v) {
		    $subExtensions[] = new Extension(url: 'procedure', value: $v);
		}
		if ($this->timeX !== null) {
		    $subExtensions[] = new Extension(url: 'time[x]', value: $this->timeX);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-manipulation',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$description = null;
		$procedure = [];
		$timeX = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'description' && $ext->value instanceof StringPrimitive) {
		        $description = $ext->value;
		    }
		    if ($extUrl === 'procedure' && $ext->value instanceof CodeableReference) {
		        $procedure[] = $ext->value;
		    }
		    if ($extUrl === 'time[x]' && $ext->value instanceof DateTimePrimitive) {
		        $timeX = $ext->value;
		    }
		}

		return new static($description, $procedure, $timeX, $id);
	}
}
