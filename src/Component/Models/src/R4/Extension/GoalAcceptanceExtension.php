<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Patient Care
 * @see http://hl7.org/fhir/StructureDefinition/goal-acceptance
 * @description Information about the acceptance and relative priority assigned to the goal by the patient, practitioners and other stake-holders. This acceptance extension was elevated to the base Goal resource.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/goal-acceptance', fhirVersion: 'R4')]
class GoalAcceptanceExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var Reference individual Individual whose acceptance is reflected */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $individual,
		/** @var CodePrimitive|null status agree | disagree | pending */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $status = null,
		/** @var CodeableConcept|null priority Priority of goal for individual */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $priority = null,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'individual', value: $this->individual);
		if ($this->status !== null) {
		    $subExtensions[] = new Extension(url: 'status', value: $this->status);
		}
		if ($this->priority !== null) {
		    $subExtensions[] = new Extension(url: 'priority', value: $this->priority);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/goal-acceptance',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$individual = null;
		$status = null;
		$priority = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'individual' && $ext->value instanceof Reference) {
		        $individual = $ext->value;
		    }
		    if ($extUrl === 'status' && $ext->value instanceof CodePrimitive) {
		        $status = $ext->value;
		    }
		    if ($extUrl === 'priority' && $ext->value instanceof CodeableConcept) {
		        $priority = $ext->value;
		    }
		}

		return new static($individual, $status, $priority, $id);
	}
}
