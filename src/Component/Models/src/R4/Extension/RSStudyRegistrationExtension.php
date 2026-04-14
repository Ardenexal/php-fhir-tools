<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Biomedical Research and Regulation
 * @see http://hl7.org/fhir/StructureDefinition/researchStudy-studyRegistration
 * @description Dates for study registration activities.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/researchStudy-studyRegistration', fhirVersion: 'R4')]
class RSStudyRegistrationExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CodeableConcept activity The specific activity */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $activity,
		/** @var bool|null actual Actual if true, else anticipated */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
		public ?bool $actual = null,
		/** @var Period|null period Date range */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'activity', value: $this->activity);
		if ($this->actual !== null) {
		    $subExtensions[] = new Extension(url: 'actual', value: $this->actual);
		}
		if ($this->period !== null) {
		    $subExtensions[] = new Extension(url: 'period', value: $this->period);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/researchStudy-studyRegistration',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$activity = null;
		$actual = null;
		$period = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'activity' && $ext->value instanceof CodeableConcept) {
		        $activity = $ext->value;
		    }
		    if ($extUrl === 'actual' && is_bool($ext->value)) {
		        $actual = $ext->value;
		    }
		    if ($extUrl === 'period' && $ext->value instanceof Period) {
		        $period = $ext->value;
		    }
		}

		return new static($activity, $actual, $period, $id);
	}
}
