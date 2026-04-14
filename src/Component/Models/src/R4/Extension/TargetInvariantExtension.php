<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/cqf-targetInvariant
 * @description DEPRECATED: Use the `targetConstraint` extension instead. Specifies an invariant that is enforced on instantiated resources. This extension can be applied to any element of a definitional resource (such as ActivityDefinition or Measure) and indicates that the invariant should be enforced on resources that are instantiated from the definition. For example, this extension can be used to define a constraint such as `numerator count must be less than or equal to denominator count`.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-targetInvariant', fhirVersion: 'R4')]
class TargetInvariantExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var IdPrimitive key Unique identifier */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $key,
		/** @var CodePrimitive severity error | warning */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $severity,
		/** @var Expression expression The invariant expression */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Expression', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression $expression,
		/** @var StringPrimitive|null requirements Why the invariant is defined */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $requirements = null,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'key', value: $this->key);
		$subExtensions[] = new Extension(url: 'severity', value: $this->severity);
		$subExtensions[] = new Extension(url: 'expression', value: $this->expression);
		if ($this->requirements !== null) {
		    $subExtensions[] = new Extension(url: 'requirements', value: $this->requirements);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/cqf-targetInvariant',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$key = null;
		$requirements = null;
		$severity = null;
		$expression = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'key' && $ext->value instanceof IdPrimitive) {
		        $key = $ext->value;
		    }
		    if ($extUrl === 'requirements' && $ext->value instanceof StringPrimitive) {
		        $requirements = $ext->value;
		    }
		    if ($extUrl === 'severity' && $ext->value instanceof CodePrimitive) {
		        $severity = $ext->value;
		    }
		    if ($extUrl === 'expression' && $ext->value instanceof Expression) {
		        $expression = $ext->value;
		    }
		}

		return new static($key, $requirements, $severity, $expression, $id);
	}
}
