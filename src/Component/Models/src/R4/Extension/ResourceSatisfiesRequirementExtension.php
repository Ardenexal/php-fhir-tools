<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/satisfies-requirement
 * @description References a requirement that this element satisfies.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/satisfies-requirement', fhirVersion: 'R4')]
class ResourceSatisfiesRequirementExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CanonicalPrimitive reference Source reference. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $reference,
		/** @var array<IdPrimitive> key Key that identifies requirement. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'id', propertyKind: 'primitive', isArray: true)]
		public array $key = [],
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'reference', value: $this->reference);
		foreach ($this->key as $v) {
		    $subExtensions[] = new Extension(url: 'key', value: $v);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/satisfies-requirement',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$reference = null;
		$key = [];

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'reference' && $ext->value instanceof CanonicalPrimitive) {
		        $reference = $ext->value;
		    }
		    if ($extUrl === 'key' && $ext->value instanceof IdPrimitive) {
		        $key[] = $ext->value;
		    }
		}

		return new static($reference, $key, $id);
	}
}
