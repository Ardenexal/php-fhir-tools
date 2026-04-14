<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/allergyintolerance-substanceExposureRisk
 *
 * @description A complex extension allowing structured capture of the exposure risk of the patient for an adverse reaction (allergy or intolerance) to the specified substance/product.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/allergyintolerance-substanceExposureRisk', fhirVersion: 'R4')]
class SubstanceExposureRiskExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept substance Substance (or pharmaceutical product) */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $substance,
        /** @var CodeableConcept exposureRisk known-reaction-risk | no-known-reaction-risk */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $exposureRisk,
        ?string $id = null,
    ) {
        $subExtensions   = [];
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
     *
     * @param array<FHIRExtensionInterface> $subExtensions
     * @param string|null                   $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
    {
        $substance    = null;
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
