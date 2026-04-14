<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Orders and Observations
 * @see http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-processing
 * @description Extension for processing of a biologically derived product.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-processing', fhirVersion: 'R4')]
class BDPProcessingExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var StringPrimitive|null description Processing of description */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $description = null,
		/** @var CodeableReference|null procedure Procesing procedure */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableReference $procedure = null,
		/** @var Reference|null additive Substance added during processing */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $additive = null,
		/** @var DateTimePrimitive|null timeX Time of processing */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $timeX = null,
		?string $id = null,
	) {
		$subExtensions = [];
		if ($this->description !== null) {
		    $subExtensions[] = new Extension(url: 'description', value: $this->description);
		}
		if ($this->procedure !== null) {
		    $subExtensions[] = new Extension(url: 'procedure', value: $this->procedure);
		}
		if ($this->additive !== null) {
		    $subExtensions[] = new Extension(url: 'additive', value: $this->additive);
		}
		if ($this->timeX !== null) {
		    $subExtensions[] = new Extension(url: 'time[x]', value: $this->timeX);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-processing',
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
		$procedure = null;
		$additive = null;
		$timeX = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'description' && $ext->value instanceof StringPrimitive) {
		        $description = $ext->value;
		    }
		    if ($extUrl === 'procedure' && $ext->value instanceof CodeableReference) {
		        $procedure = $ext->value;
		    }
		    if ($extUrl === 'additive' && $ext->value instanceof Reference) {
		        $additive = $ext->value;
		    }
		    if ($extUrl === 'time[x]' && $ext->value instanceof DateTimePrimitive) {
		        $timeX = $ext->value;
		    }
		}

		return new static($description, $procedure, $additive, $timeX, $id);
	}
}
