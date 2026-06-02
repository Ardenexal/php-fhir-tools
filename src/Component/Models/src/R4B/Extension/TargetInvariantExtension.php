<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-targetInvariant
 *
 * @description DEPRECATED: Use the `targetConstraint` extension instead. Specifies an invariant that is enforced on instantiated resources. This extension can be applied to any element of a definitional resource (such as ActivityDefinition or Measure) and indicates that the invariant should be enforced on resources that are instantiated from the definition. For example, this extension can be used to define a constraint such as `numerator count must be less than or equal to denominator count`.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-targetInvariant', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.code')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.intent')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.priority')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.doNotPerform')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.timing[x]')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.asNeeded[x]')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.location')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.participant')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.product[x]')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.quantity')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.dosage')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.bodySite')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.dynamicValue')]
#[FHIRExtensionContext(type: 'element', expression: 'Measure')]
#[FHIRExtensionContext(type: 'element', expression: 'Measure.group')]
#[FHIRExtensionContext(type: 'element', expression: 'Measure.group.population')]
#[FHIRExtensionContext(type: 'element', expression: 'Measure.group.stratifier')]
#[FHIRExtensionContext(type: 'element', expression: 'Measure.supplementalData')]
#[FHIRExtensionContext(type: 'element', expression: 'PlanDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'PlanDefinition.goal')]
#[FHIRExtensionContext(type: 'element', expression: 'PlanDefinition.goal.target')]
#[FHIRExtensionContext(type: 'element', expression: 'PlanDefinition.actor')]
#[FHIRExtensionContext(type: 'element', expression: 'PlanDefinition.action')]
#[FHIRExtensionContext(type: 'element', expression: 'Questionnaire')]
#[FHIRExtensionContext(type: 'element', expression: 'Questionnaire.item')]
class TargetInvariantExtension extends Extension implements FHIRComplexExtensionInterface
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
        /** @var StringPrimitive|null requirements Why the invariant is defined */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $requirements = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
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

        if ($key === null) {
            throw new \InvalidArgumentException('Required sub-extension "key" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($severity === null) {
            throw new \InvalidArgumentException('Required sub-extension "severity" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($expression === null) {
            throw new \InvalidArgumentException('Required sub-extension "expression" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($key, $requirements, $severity, $expression, $id);
    }
}
