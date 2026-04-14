<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Pharmacy
 * @see http://hl7.org/fhir/StructureDefinition/medication-manufacturingBatch
 * @description The date at which the drug substance or drug product was manufactured.  The specific operation/step in the process used to determine the date is specified by the manufacturingDateClassification element.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/medication-manufacturingBatch', fhirVersion: 'R4')]
class MedManufacturingBatchExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var DateTimePrimitive|null manufacturingDate Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $manufacturingDate = null,
		/** @var CodeableConcept|null manufacturingDateClassification Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $manufacturingDateClassification = null,
		/** @var Reference|null assignedManufacturer Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $assignedManufacturer = null,
		/** @var CodeableConcept|null expirationDateClassification Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $expirationDateClassification = null,
		/** @var CodeableConcept|null batchUtilization Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $batchUtilization = null,
		/** @var Quantity|null batchQuantity Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $batchQuantity = null,
		/** @var StringPrimitive|null additionalInformation Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $additionalInformation = null,
		/** @var array<Base64BinaryPrimitive> container Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive', isArray: true)]
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
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$manufacturingDate = null;
		$manufacturingDateClassification = null;
		$assignedManufacturer = null;
		$expirationDateClassification = null;
		$batchUtilization = null;
		$batchQuantity = null;
		$additionalInformation = null;
		$container = [];

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
