<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/relative-date
 * @description Specifies that a date is relative to some event. The event happens [Duration] after [Event]. DEPRECATED: This extension has been deprecated in favor of the new relative-time extension.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/relative-date', fhirVersion: 'R4')]
class RelativeDateCriteriaExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var Reference targetReference Specific event that the date is relative to */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $targetReference,
		/** @var CodeableConcept targetCode Kind of event that the date is relative to */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $targetCode,
		/** @var CodePrimitive relationship before-start | before | before-end | concurrent-with-start | concurrent | concurrent-with-end | after-start | after | after-end */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $relationship,
		/** @var Duration offset Duration after the event */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration $offset,
		/** @var StringPrimitive|null targetPath Relative to which element on the event */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $targetPath = null,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'targetReference', value: $this->targetReference);
		$subExtensions[] = new Extension(url: 'targetCode', value: $this->targetCode);
		$subExtensions[] = new Extension(url: 'relationship', value: $this->relationship);
		$subExtensions[] = new Extension(url: 'offset', value: $this->offset);
		if ($this->targetPath !== null) {
		    $subExtensions[] = new Extension(url: 'targetPath', value: $this->targetPath);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/relative-date',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$targetReference = null;
		$targetCode = null;
		$targetPath = null;
		$relationship = null;
		$offset = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'targetReference' && $ext->value instanceof Reference) {
		        $targetReference = $ext->value;
		    }
		    if ($extUrl === 'targetCode' && $ext->value instanceof CodeableConcept) {
		        $targetCode = $ext->value;
		    }
		    if ($extUrl === 'targetPath' && $ext->value instanceof StringPrimitive) {
		        $targetPath = $ext->value;
		    }
		    if ($extUrl === 'relationship' && $ext->value instanceof CodePrimitive) {
		        $relationship = $ext->value;
		    }
		    if ($extUrl === 'offset' && $ext->value instanceof Duration) {
		        $offset = $ext->value;
		    }
		}

		return new static($targetReference, $targetCode, $targetPath, $relationship, $offset, $id);
	}
}
