<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/cqf-definitionTerm
 * @description Specifies a term and its associated definition.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-definitionTerm', fhirVersion: 'R4')]
class DefinitionTermExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var StringPrimitive term Term being defined */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $term,
		/** @var MarkdownPrimitive definition Definition of the term */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $definition,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'term', value: $this->term);
		$subExtensions[] = new Extension(url: 'definition', value: $this->definition);
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/cqf-definitionTerm',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$term = null;
		$definition = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'term' && $ext->value instanceof StringPrimitive) {
		        $term = $ext->value;
		    }
		    if ($extUrl === 'definition' && $ext->value instanceof MarkdownPrimitive) {
		        $definition = $ext->value;
		    }
		}

		return new static($term, $definition, $id);
	}
}
