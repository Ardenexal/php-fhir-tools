<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/targetConstraint
 * @description Specifies a constraint that is enforced on instantiated (or target) resources. This extension can be applied to definitional resources (such as ActivityDefinition or Measure) and indicates that the constraint should be enforced on resources that are instantiated from the definition. For example, this extension can be used to define a constraint such as `numerator count must be less than or equal to denominator count`.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/targetConstraint', fhirVersion: 'R4')]
class TargetConstraintExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var IdPrimitive key Unique identifier */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $key,
		/** @var MarkdownPrimitive|null requirements Why the constraint is defined */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $requirements = null,
		/** @var CodePrimitive severity error | warning */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $severity,
		/** @var Expression expression The invariant expression */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Expression', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression $expression,
		/** @var StringPrimitive human Human-readable rule */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $human,
		/** @var array<StringPrimitive> location Relative path to elements */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
		public array $location = [],
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'key', value: $this->key);
		if ($this->requirements !== null) {
		    $subExtensions[] = new Extension(url: 'requirements', value: $this->requirements);
		}
		$subExtensions[] = new Extension(url: 'severity', value: $this->severity);
		$subExtensions[] = new Extension(url: 'expression', value: $this->expression);
		$subExtensions[] = new Extension(url: 'human', value: $this->human);
		foreach ($this->location as $v) {
		    $subExtensions[] = new Extension(url: 'location', value: $v);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/targetConstraint',
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
		$human = null;
		$location = [];

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'key' && $ext->value instanceof IdPrimitive) {
		        $key = $ext->value;
		    }
		    if ($extUrl === 'requirements' && $ext->value instanceof MarkdownPrimitive) {
		        $requirements = $ext->value;
		    }
		    if ($extUrl === 'severity' && $ext->value instanceof CodePrimitive) {
		        $severity = $ext->value;
		    }
		    if ($extUrl === 'expression' && $ext->value instanceof Expression) {
		        $expression = $ext->value;
		    }
		    if ($extUrl === 'human' && $ext->value instanceof StringPrimitive) {
		        $human = $ext->value;
		    }
		    if ($extUrl === 'location' && $ext->value instanceof StringPrimitive) {
		        $location[] = $ext->value;
		    }
		}

		return new static($key, $requirements, $severity, $expression, $human, $location, $id);
	}
}
