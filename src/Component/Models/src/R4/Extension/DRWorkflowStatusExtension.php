<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Orders and Observations
 * @see http://hl7.org/fhir/StructureDefinition/diagnosticReport-workflowStatus
 * @description The workflow status of the diagnostic report.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/diagnosticReport-workflowStatus', fhirVersion: 'R4')]
class DRWorkflowStatusExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CodeableConcept valueSlice Meaning of the status */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $valueSlice,
		/** @var InstantPrimitive timestamp Status timestamp */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'instant', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive $timestamp,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'value', value: $this->valueSlice);
		$subExtensions[] = new Extension(url: 'timestamp', value: $this->timestamp);
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/diagnosticReport-workflowStatus',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$valueSlice = null;
		$timestamp = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'value' && $ext->value instanceof CodeableConcept) {
		        $valueSlice = $ext->value;
		    }
		    if ($extUrl === 'timestamp' && $ext->value instanceof InstantPrimitive) {
		        $timestamp = $ext->value;
		    }
		}

		return new static($valueSlice, $timestamp, $id);
	}
}
