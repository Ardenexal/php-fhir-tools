<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/individual-recordedSexOrGender
 *
 * @description Recorded sex or gender (RSG) information includes the various sex and gender concepts that are often used in existing systems but are known NOT to represent a gender identity, sex parameter for clinical use, or attributes related to sexuality, such as sexual orientation, sexual activity, or sexual attraction. Examples of recorded sex or gender concepts include administrative gender, administrative sex, and sex assigned at birth.  When exchanging this concept, refer to the guidance in the [Gender Harmony Implementation Guide](http://hl7.org/xprod/ig/uv/gender-harmony/).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/individual-recordedSexOrGender', fhirVersion: 'R5')]
class RecordedSexOrGenderExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept valueSlice The recorded sex or gender property for the individual */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $valueSlice,
        /** @var CodeableConcept|null type Type of recorded sex or gender. */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var Period|null effectivePeriod When the recorded sex or gender value applies */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $effectivePeriod = null,
        /** @var DateTimePrimitive|null acquisitionDate When the sex or gender value was recorded. */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $acquisitionDate = null,
        /** @var CodeableConcept|null source The source of the Recorded Sex or Gender value. */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $source = null,
        /** @var CodeableConcept|null sourceDocument The document the sex or gender property was acquired from. */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $sourceDocument = null,
        /** @var StringPrimitive|null sourceField The name of the field within the source document where this sex or gender property is initially recorded. */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $sourceField = null,
        /** @var CodeableConcept|null jurisdiction Who issued the document where the sex or gender was aquired */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $jurisdiction = null,
        /** @var StringPrimitive|null comment Context or source information about the recorded sex or gender */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $comment = null,
        /** @var bool|null genderElementQualifier Whether this recorded sex or gender value qualifies the .gender element. */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $genderElementQualifier = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'value', value: $this->valueSlice);
        if ($this->type !== null) {
            $subExtensions[] = new Extension(url: 'type', value: $this->type);
        }
        if ($this->effectivePeriod !== null) {
            $subExtensions[] = new Extension(url: 'effectivePeriod', value: $this->effectivePeriod);
        }
        if ($this->acquisitionDate !== null) {
            $subExtensions[] = new Extension(url: 'acquisitionDate', value: $this->acquisitionDate);
        }
        if ($this->source !== null) {
            $subExtensions[] = new Extension(url: 'source', value: $this->source);
        }
        if ($this->sourceDocument !== null) {
            $subExtensions[] = new Extension(url: 'sourceDocument', value: $this->sourceDocument);
        }
        if ($this->sourceField !== null) {
            $subExtensions[] = new Extension(url: 'sourceField', value: $this->sourceField);
        }
        if ($this->jurisdiction !== null) {
            $subExtensions[] = new Extension(url: 'jurisdiction', value: $this->jurisdiction);
        }
        if ($this->comment !== null) {
            $subExtensions[] = new Extension(url: 'comment', value: $this->comment);
        }
        if ($this->genderElementQualifier !== null) {
            $subExtensions[] = new Extension(url: 'genderElementQualifier', value: $this->genderElementQualifier);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/individual-recordedSexOrGender',
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
        $valueSlice             = null;
        $type                   = null;
        $effectivePeriod        = null;
        $acquisitionDate        = null;
        $source                 = null;
        $sourceDocument         = null;
        $sourceField            = null;
        $jurisdiction           = null;
        $comment                = null;
        $genderElementQualifier = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'value' && $ext->value instanceof CodeableConcept) {
                $valueSlice = $ext->value;
            }
            if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
                $type = $ext->value;
            }
            if ($extUrl === 'effectivePeriod' && $ext->value instanceof Period) {
                $effectivePeriod = $ext->value;
            }
            if ($extUrl === 'acquisitionDate' && $ext->value instanceof DateTimePrimitive) {
                $acquisitionDate = $ext->value;
            }
            if ($extUrl === 'source' && $ext->value instanceof CodeableConcept) {
                $source = $ext->value;
            }
            if ($extUrl === 'sourceDocument' && $ext->value instanceof CodeableConcept) {
                $sourceDocument = $ext->value;
            }
            if ($extUrl === 'sourceField' && $ext->value instanceof StringPrimitive) {
                $sourceField = $ext->value;
            }
            if ($extUrl === 'jurisdiction' && $ext->value instanceof CodeableConcept) {
                $jurisdiction = $ext->value;
            }
            if ($extUrl === 'comment' && $ext->value instanceof StringPrimitive) {
                $comment = $ext->value;
            }
            if ($extUrl === 'genderElementQualifier' && is_bool($ext->value)) {
                $genderElementQualifier = $ext->value;
            }
        }

        if ($valueSlice === null) {
            throw new \InvalidArgumentException('Required sub-extension "value" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($valueSlice, $type, $effectivePeriod, $acquisitionDate, $source, $sourceDocument, $sourceField, $jurisdiction, $comment, $genderElementQualifier, $id);
    }
}
