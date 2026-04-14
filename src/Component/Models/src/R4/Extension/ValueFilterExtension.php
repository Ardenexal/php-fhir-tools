<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/cqf-valueFilter
 * @description Allows additional value-based filters to be specified as part of a data requirement.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-valueFilter', fhirVersion: 'R4')]
class ValueFilterExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var StringPrimitive|null path Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $path = null,
		/** @var StringPrimitive|null searchParam Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $searchParam = null,
		/** @var CodePrimitive comparator Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $comparator,
		/** @var bool|null valueSlice Extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
		public ?bool $valueSlice = null,
		?string $id = null,
	) {
		$subExtensions = [];
		if ($this->path !== null) {
		    $subExtensions[] = new Extension(url: 'path', value: $this->path);
		}
		if ($this->searchParam !== null) {
		    $subExtensions[] = new Extension(url: 'searchParam', value: $this->searchParam);
		}
		$subExtensions[] = new Extension(url: 'comparator', value: $this->comparator);
		if ($this->valueSlice !== null) {
		    $subExtensions[] = new Extension(url: 'value', value: $this->valueSlice);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/cqf-valueFilter',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$path = null;
		$searchParam = null;
		$comparator = null;
		$valueSlice = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'path' && $ext->value instanceof StringPrimitive) {
		        $path = $ext->value;
		    }
		    if ($extUrl === 'searchParam' && $ext->value instanceof StringPrimitive) {
		        $searchParam = $ext->value;
		    }
		    if ($extUrl === 'comparator' && $ext->value instanceof CodePrimitive) {
		        $comparator = $ext->value;
		    }
		    if ($extUrl === 'value' && is_bool($ext->value)) {
		        $valueSlice = $ext->value;
		    }
		}

		return new static($path, $searchParam, $comparator, $valueSlice, $id);
	}
}
