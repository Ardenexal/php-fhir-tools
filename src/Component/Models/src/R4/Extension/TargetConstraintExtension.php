<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/targetConstraint
 *
 * @description Specifies a constraint that is enforced on instantiated (or target) resources. This extension can be applied to definitional resources (such as ActivityDefinition or Measure) and indicates that the constraint should be enforced on resources that are instantiated from the definition. For example, this extension can be used to define a constraint such as `numerator count must be less than or equal to denominator count`.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/targetConstraint', fhirVersion: 'R4')]
class TargetConstraintExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var IdPrimitive key Unique identifier */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public IdPrimitive $key,
        /** @var CodePrimitive severity error | warning */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $severity,
        /** @var Expression expression The invariant expression */
        #[FhirProperty(fhirType: 'Expression', propertyKind: 'complex')]
        public Expression $expression,
        /** @var StringPrimitive human Human-readable rule */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $human,
        /** @var MarkdownPrimitive|null requirements Why the constraint is defined */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $requirements = null,
        /** @var array<StringPrimitive> location Relative path to elements */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $location = [],
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'key', value: $this->key);
        $subExtensions[] = new Extension(url: 'severity', value: $this->severity);
        $subExtensions[] = new Extension(url: 'expression', value: $this->expression);
        $subExtensions[] = new Extension(url: 'human', value: $this->human);
        if ($this->requirements !== null) {
            $subExtensions[] = new Extension(url: 'requirements', value: $this->requirements);
        }
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
     *
     * @param array<FHIRExtensionInterface> $subExtensions
     * @param string|null                   $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
    {
        $key          = null;
        $requirements = null;
        $severity     = null;
        $expression   = null;
        $human        = null;
        $location     = [];

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

        if ($key === null) {
            throw new \InvalidArgumentException('Required sub-extension "key" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($severity === null) {
            throw new \InvalidArgumentException('Required sub-extension "severity" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($expression === null) {
            throw new \InvalidArgumentException('Required sub-extension "expression" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($human === null) {
            throw new \InvalidArgumentException('Required sub-extension "human" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($key, $requirements, $severity, $expression, $human, $location, $id);
    }
}
