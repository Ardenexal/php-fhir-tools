<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/cqf-measureInfo
 * @description The measure criteria that resulted in the resource being included in the result of a measure evaluation. The extension can be used on the resource directly, or it can be used on a Reference element such as MeasureReport.evaluatedResource to identify the measure criteria in the reference (i.e. without requiring the referenced resource to be changed by adding an extension).
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-measureInfo', fhirVersion: 'R4')]
class MeasureInfoExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CanonicalPrimitive|null measure The measure being calculated */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $measure = null,
		/** @var StringPrimitive|null groupId The group identifier */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $groupId = null,
		/** @var StringPrimitive|null populationId The population identifier */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $populationId = null,
		?string $id = null,
	) {
		$subExtensions = [];
		if ($this->measure !== null) {
		    $subExtensions[] = new Extension(url: 'measure', value: $this->measure);
		}
		if ($this->groupId !== null) {
		    $subExtensions[] = new Extension(url: 'groupId', value: $this->groupId);
		}
		if ($this->populationId !== null) {
		    $subExtensions[] = new Extension(url: 'populationId', value: $this->populationId);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/cqf-measureInfo',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$measure = null;
		$groupId = null;
		$populationId = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'measure' && $ext->value instanceof CanonicalPrimitive) {
		        $measure = $ext->value;
		    }
		    if ($extUrl === 'groupId' && $ext->value instanceof StringPrimitive) {
		        $groupId = $ext->value;
		    }
		    if ($extUrl === 'populationId' && $ext->value instanceof StringPrimitive) {
		        $populationId = $ext->value;
		    }
		}

		return new static($measure, $groupId, $populationId, $id);
	}
}
