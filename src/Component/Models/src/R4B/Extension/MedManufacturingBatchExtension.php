<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Pharmacy
 *
 * @see http://hl7.org/fhir/StructureDefinition/medication-manufacturingBatch
 *
 * @description The date at which the drug substance or drug product was manufactured.  The specific operation/step in the process used to determine the date is specified by the manufacturingDateClassification element.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/medication-manufacturingBatch', fhirVersion: 'R4B')]
class MedManufacturingBatchExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var DateTimePrimitive|null manufacturingDate Extension */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $manufacturingDate = null,
        /** @var CodeableConcept|null manufacturingDateClassification Extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $manufacturingDateClassification = null,
        /** @var Reference|null assignedManufacturer Extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $assignedManufacturer = null,
        /** @var CodeableConcept|null expirationDateClassification Extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $expirationDateClassification = null,
        /** @var CodeableConcept|null batchUtilization Extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $batchUtilization = null,
        /** @var Quantity|null batchQuantity Extension */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $batchQuantity = null,
        /** @var StringPrimitive|null additionalInformation Extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $additionalInformation = null,
        /** @var array<Base64BinaryPrimitive> container Extension */
        #[FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive', isArray: true)]
        public array $container = [],
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->manufacturingDate !== null) {
            $subExtensions[] = new Extension(url: 'manufacturingDate', value: $this->manufacturingDate);
        }
        if ($this->manufacturingDateClassification !== null) {
            $subExtensions[] = new Extension(url: 'manufacturingDateClassification', value: $this->manufacturingDateClassification);
        }
        if ($this->assignedManufacturer !== null) {
            $subExtensions[] = new Extension(url: 'assignedManufacturer', value: $this->assignedManufacturer);
        }
        if ($this->expirationDateClassification !== null) {
            $subExtensions[] = new Extension(url: 'expirationDateClassification', value: $this->expirationDateClassification);
        }
        if ($this->batchUtilization !== null) {
            $subExtensions[] = new Extension(url: 'batchUtilization', value: $this->batchUtilization);
        }
        if ($this->batchQuantity !== null) {
            $subExtensions[] = new Extension(url: 'batchQuantity', value: $this->batchQuantity);
        }
        if ($this->additionalInformation !== null) {
            $subExtensions[] = new Extension(url: 'additionalInformation', value: $this->additionalInformation);
        }
        foreach ($this->container as $v) {
            $subExtensions[] = new Extension(url: 'container', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/medication-manufacturingBatch',
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
        $manufacturingDate               = null;
        $manufacturingDateClassification = null;
        $assignedManufacturer            = null;
        $expirationDateClassification    = null;
        $batchUtilization                = null;
        $batchQuantity                   = null;
        $additionalInformation           = null;
        $container                       = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'manufacturingDate' && $ext->value instanceof DateTimePrimitive) {
                $manufacturingDate = $ext->value;
            }
            if ($extUrl === 'manufacturingDateClassification' && $ext->value instanceof CodeableConcept) {
                $manufacturingDateClassification = $ext->value;
            }
            if ($extUrl === 'assignedManufacturer' && $ext->value instanceof Reference) {
                $assignedManufacturer = $ext->value;
            }
            if ($extUrl === 'expirationDateClassification' && $ext->value instanceof CodeableConcept) {
                $expirationDateClassification = $ext->value;
            }
            if ($extUrl === 'batchUtilization' && $ext->value instanceof CodeableConcept) {
                $batchUtilization = $ext->value;
            }
            if ($extUrl === 'batchQuantity' && $ext->value instanceof Quantity) {
                $batchQuantity = $ext->value;
            }
            if ($extUrl === 'additionalInformation' && $ext->value instanceof StringPrimitive) {
                $additionalInformation = $ext->value;
            }
            if ($extUrl === 'container' && $ext->value instanceof Base64BinaryPrimitive) {
                $container[] = $ext->value;
            }
        }

        return new static($manufacturingDate, $manufacturingDateClassification, $assignedManufacturer, $expirationDateClassification, $batchUtilization, $batchQuantity, $additionalInformation, $container, $id);
    }
}
