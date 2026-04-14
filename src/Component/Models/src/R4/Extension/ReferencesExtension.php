<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Orders and Observations
 * @see http://hl7.org/fhir/StructureDefinition/DiagnosticReport-geneticsReferences
 * @description Additional bibliographic reference information about genetics, medications, clinical trials, etc. associated with knowledge-based information on genetics/genetic condition.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/DiagnosticReport-geneticsReferences', fhirVersion: 'R4')]
class ReferencesExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var StringPrimitive|null description Reference description */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $description = null,
		/** @var array<UriPrimitive> reference Reference URI */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isArray: true)]
		public array $reference = [],
		/** @var CodeableConcept|null type Reference type */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		?string $id = null,
	) {
		$subExtensions = [];
		if ($this->description !== null) {
		    $subExtensions[] = new Extension(url: 'description', value: $this->description);
		}
		foreach ($this->reference as $v) {
		    $subExtensions[] = new Extension(url: 'reference', value: $v);
		}
		if ($this->type !== null) {
		    $subExtensions[] = new Extension(url: 'type', value: $this->type);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/DiagnosticReport-geneticsReferences',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$description = null;
		$reference = [];
		$type = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'description' && $ext->value instanceof StringPrimitive) {
		        $description = $ext->value;
		    }
		    if ($extUrl === 'reference' && $ext->value instanceof UriPrimitive) {
		        $reference[] = $ext->value;
		    }
		    if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
		        $type = $ext->value;
		    }
		}

		return new static($description, $reference, $type, $id);
	}
}
