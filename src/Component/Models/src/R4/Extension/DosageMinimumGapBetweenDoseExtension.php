<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/dosage-minimumGapBetweenDose
 * @description The minimum amount of time that must pass between administering the specified dose amount.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/dosage-minimumGapBetweenDose', fhirVersion: 'R4')]
class DosageMinimumGapBetweenDoseExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var array<Base64BinaryPrimitive> meetGoal Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive', isArray: true)]
		public array $meetGoal = [],
		/** @var array<Base64BinaryPrimitive> whenTrigger Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive', isArray: true)]
		public array $whenTrigger = [],
		/** @var array<Base64BinaryPrimitive> precondition Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive', isArray: true)]
		public array $precondition = [],
		?string $id = null,
	) {
		$subExtensions = [];
		foreach ($this->meetGoal as $v) {
		    $subExtensions[] = new Extension(url: 'meetGoal', value: $v);
		}
		foreach ($this->whenTrigger as $v) {
		    $subExtensions[] = new Extension(url: 'whenTrigger', value: $v);
		}
		foreach ($this->precondition as $v) {
		    $subExtensions[] = new Extension(url: 'precondition', value: $v);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/dosage-minimumGapBetweenDose',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$meetGoal = [];
		$whenTrigger = [];
		$precondition = [];

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'meetGoal' && $ext->value instanceof Base64BinaryPrimitive) {
		        $meetGoal[] = $ext->value;
		    }
		    if ($extUrl === 'whenTrigger' && $ext->value instanceof Base64BinaryPrimitive) {
		        $whenTrigger[] = $ext->value;
		    }
		    if ($extUrl === 'precondition' && $ext->value instanceof Base64BinaryPrimitive) {
		        $precondition[] = $ext->value;
		    }
		}

		return new static($meetGoal, $whenTrigger, $precondition, $id);
	}
}
