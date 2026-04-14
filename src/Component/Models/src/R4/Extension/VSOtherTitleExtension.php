<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Terminology Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/valueset-otherTitle
 * @description Human readable titles for the valueset.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-otherTitle', fhirVersion: 'R4')]
class VSOtherTitleExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var StringPrimitive title Human readable, short and specific */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $title,
		/** @var bool|null preferred Which Title is preferred for this language */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
		public ?bool $preferred = null,
		/** @var CodePrimitive|null language Which language this title is in */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $language = null,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'title', value: $this->title);
		if ($this->preferred !== null) {
		    $subExtensions[] = new Extension(url: 'preferred', value: $this->preferred);
		}
		if ($this->language !== null) {
		    $subExtensions[] = new Extension(url: 'language', value: $this->language);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/valueset-otherTitle',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$title = null;
		$preferred = null;
		$language = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'title' && $ext->value instanceof StringPrimitive) {
		        $title = $ext->value;
		    }
		    if ($extUrl === 'preferred' && is_bool($ext->value)) {
		        $preferred = $ext->value;
		    }
		    if ($extUrl === 'language' && $ext->value instanceof CodePrimitive) {
		        $language = $ext->value;
		    }
		}

		return new static($title, $preferred, $language, $id);
	}
}
