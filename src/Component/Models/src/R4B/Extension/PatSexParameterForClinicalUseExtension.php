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
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/patient-sexParameterForClinicalUse
 *
 * @description A Sex Parameter for Clinical Use is a parameter that provides guidance on how a recipient should apply settings or reference ranges that are derived from observable information such as an organ inventory, recent hormone lab tests, genetic testing, menstrual status, obstetric history, etc..  This property is intended for use in clinical decision making, and indicates that treatment or diagnostic tests should consider best practices associated with the relevant reference population.  When exchanging these concepts, refer to the guidance in the [Gender Harmony Implementation Guide](http://hl7.org/xprod/ig/uv/gender-harmony/).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-sexParameterForClinicalUse', fhirVersion: 'R4B')]
class PatSexParameterForClinicalUseExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept valueSlice A context-specific sex parameter for clinical use */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $valueSlice,
        /** @var Period|null period When the sex for clinical use applies */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $period = null,
        /** @var StringPrimitive|null comment Comments about the sex parameter for clinical use */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $comment = null,
        /** @var array<CodeableConcept> supportingInfo Source of the sex for clincal use */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
        public array $supportingInfo = [],
        /** @var array<CodeableConcept> intendedClinicalUse The intended clinical uses for this sex parameter */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
        public array $intendedClinicalUse = [],
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'value', value: $this->valueSlice);
        if ($this->period !== null) {
            $subExtensions[] = new Extension(url: 'period', value: $this->period);
        }
        if ($this->comment !== null) {
            $subExtensions[] = new Extension(url: 'comment', value: $this->comment);
        }
        foreach ($this->supportingInfo as $v) {
            $subExtensions[] = new Extension(url: 'supportingInfo', value: $v);
        }
        foreach ($this->intendedClinicalUse as $v) {
            $subExtensions[] = new Extension(url: 'intendedClinicalUse', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/patient-sexParameterForClinicalUse',
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
        $valueSlice          = null;
        $period              = null;
        $comment             = null;
        $supportingInfo      = [];
        $intendedClinicalUse = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'value' && $ext->value instanceof CodeableConcept) {
                $valueSlice = $ext->value;
            }
            if ($extUrl === 'period' && $ext->value instanceof Period) {
                $period = $ext->value;
            }
            if ($extUrl === 'comment' && $ext->value instanceof StringPrimitive) {
                $comment = $ext->value;
            }
            if ($extUrl === 'supportingInfo' && $ext->value instanceof CodeableConcept) {
                $supportingInfo[] = $ext->value;
            }
            if ($extUrl === 'intendedClinicalUse' && $ext->value instanceof CodeableConcept) {
                $intendedClinicalUse[] = $ext->value;
            }
        }

        if ($valueSlice === null) {
            throw new \InvalidArgumentException('Required sub-extension "value" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($valueSlice, $period, $comment, $supportingInfo, $intendedClinicalUse, $id);
    }
}
