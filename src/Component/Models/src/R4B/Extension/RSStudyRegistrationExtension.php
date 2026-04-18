<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period;

/**
 * @author HL7 International / Biomedical Research and Regulation
 *
 * @see http://hl7.org/fhir/StructureDefinition/researchStudy-studyRegistration
 *
 * @description Dates for study registration activities.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/researchStudy-studyRegistration', fhirVersion: 'R4B')]
class RSStudyRegistrationExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept activity The specific activity */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $activity,
        /** @var bool|null actual Actual if true, else anticipated */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $actual = null,
        /** @var Period|null period Date range */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $period = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
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
     *
     * @param array<FHIRExtensionInterface> $subExtensions
     * @param string|null                   $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
    {
        $activity = null;
        $actual   = null;
        $period   = null;

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

        if ($activity === null) {
            throw new \InvalidArgumentException('Required sub-extension "activity" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($activity, $actual, $period, $id);
    }
}
