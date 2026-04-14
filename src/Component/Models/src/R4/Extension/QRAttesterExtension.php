<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/questionnaireresponse-attester
 * @description Allows capturing the individual(s) who attest to the accuracy of the information within the QuestionnaireResponse.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaireresponse-attester', fhirVersion: 'R4')]
class QRAttesterExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CodePrimitive mode Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $mode,
		/** @var DateTimePrimitive|null time Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $time = null,
		/** @var Reference|null party Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $party = null,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'mode', value: $this->mode);
		if ($this->time !== null) {
		    $subExtensions[] = new Extension(url: 'time', value: $this->time);
		}
		if ($this->party !== null) {
		    $subExtensions[] = new Extension(url: 'party', value: $this->party);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/questionnaireresponse-attester',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$mode = null;
		$time = null;
		$party = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'mode' && $ext->value instanceof CodePrimitive) {
		        $mode = $ext->value;
		    }
		    if ($extUrl === 'time' && $ext->value instanceof DateTimePrimitive) {
		        $time = $ext->value;
		    }
		    if ($extUrl === 'party' && $ext->value instanceof Reference) {
		        $party = $ext->value;
		    }
		}

		return new static($mode, $time, $party, $id);
	}
}
