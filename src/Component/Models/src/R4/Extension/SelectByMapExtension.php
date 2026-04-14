<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Terminology Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/valueset-select-by-map
 * @description This extension indicates that in addition to the concepts directly selected (either included or excluded) in the include/exclude statement, any source codes that are mapped to target codes that are selected by the nominated ConceptMapare also selected. The filter property can be used to restrict which types of relationships are included.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-select-by-map', fhirVersion: 'R4')]
class SelectByMapExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CanonicalPrimitive map The canonical URL for the ConceptMap */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $map,
		/** @var array<CodePrimitive> filter Include targets with this relationship in the selection */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
		public array $filter = [],
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'map', value: $this->map);
		foreach ($this->filter as $v) {
		    $subExtensions[] = new Extension(url: 'filter', value: $v);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/valueset-select-by-map',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$map = null;
		$filter = [];

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'map' && $ext->value instanceof CanonicalPrimitive) {
		        $map = $ext->value;
		    }
		    if ($extUrl === 'filter' && $ext->value instanceof CodePrimitive) {
		        $filter[] = $ext->value;
		    }
		}

		return new static($map, $filter, $id);
	}
}
