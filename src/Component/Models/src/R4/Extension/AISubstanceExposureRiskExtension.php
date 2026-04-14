<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Patient Care
 * @see http://hl7.org/fhir/StructureDefinition/allergyintolerance-substanceExposureRisk
 * @description The 'substanceExposureRisk' extension is a structured and more flexible alternative to AllergyIntolerance.code for making positive or negative allergy or intolerance statements. This extension provides the capability to make "no known allergy" (or "no risk of adverse reaction") statements regarding any coded substance/product (including cases when a pre-coordinated "no allergy to x" concept for that substance/product does not exist). If the 'substanceExposureRisk' extension is present, the AllergyIntolerance.code element SHALL be omitted.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/allergyintolerance-substanceExposureRisk', fhirVersion: 'R4')]
class AISubstanceExposureRiskExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CodeableConcept substance Substance (or pharmaceutical product) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $substance,
		/** @var CodeableConcept exposureRisk known-reaction-risk | no-known-reaction-risk */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $exposureRisk,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'substance', value: $this->substance);
		$subExtensions[] = new Extension(url: 'exposureRisk', value: $this->exposureRisk);
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/allergyintolerance-substanceExposureRisk',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$substance = null;
		$exposureRisk = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'substance' && $ext->value instanceof CodeableConcept) {
		        $substance = $ext->value;
		    }
		    if ($extUrl === 'exposureRisk' && $ext->value instanceof CodeableConcept) {
		        $exposureRisk = $ext->value;
		    }
		}

		return new static($substance, $exposureRisk, $id);
	}
}
