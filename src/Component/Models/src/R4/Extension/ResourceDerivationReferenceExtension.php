<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/derivation-reference
 * @description References a location within a set of source text from which the discrete information described by this Resource/Element was extracted.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/derivation-reference', fhirVersion: 'R4')]
class ResourceDerivationReferenceExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var Reference|null reference Source reference. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $reference = null,
		/** @var StringPrimitive|null path Element containing text. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $path = null,
		/** @var int|null offset Starting position. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
		public ?int $offset = null,
		/** @var int|null length Number of characters. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
		public ?int $length = null,
		?string $id = null,
	) {
		$subExtensions = [];
		if ($this->reference !== null) {
		    $subExtensions[] = new Extension(url: 'reference', value: $this->reference);
		}
		if ($this->path !== null) {
		    $subExtensions[] = new Extension(url: 'path', value: $this->path);
		}
		if ($this->offset !== null) {
		    $subExtensions[] = new Extension(url: 'offset', value: $this->offset);
		}
		if ($this->length !== null) {
		    $subExtensions[] = new Extension(url: 'length', value: $this->length);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/derivation-reference',
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
		$path = null;
		$offset = null;
		$length = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'reference' && $ext->value instanceof Reference) {
		        $reference = $ext->value;
		    }
		    if ($extUrl === 'path' && $ext->value instanceof StringPrimitive) {
		        $path = $ext->value;
		    }
		    if ($extUrl === 'offset' && is_int($ext->value)) {
		        $offset = $ext->value;
		    }
		    if ($extUrl === 'length' && is_int($ext->value)) {
		        $length = $ext->value;
		    }
		}

		return new static($reference, $path, $offset, $length, $id);
	}
}
