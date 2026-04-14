<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/questionnaire-constraint
 *
 * @description An invariant that must be satisfied before responses to the questionnaire can be considered "complete".
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-constraint', fhirVersion: 'R4')]
class ConstraintExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var IdPrimitive key Unique identifier */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $key,
        /** @var StringPrimitive|null requirements Why needed */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $requirements = null,
        /** @var CodePrimitive severity error|warning */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $severity,
        /** @var StringPrimitive expression Formal rule */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $expression,
        /** @var StringPrimitive human Human-readable rule */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $human,
        /** @var array<StringPrimitive> location Relative path to elements */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $location = [],
        ?string $id = null,
    ) {
        $subExtensions   = [];
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
            url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-constraint',
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
            if ($extUrl === 'requirements' && $ext->value instanceof StringPrimitive) {
                $requirements = $ext->value;
            }
            if ($extUrl === 'severity' && $ext->value instanceof CodePrimitive) {
                $severity = $ext->value;
            }
            if ($extUrl === 'expression' && $ext->value instanceof StringPrimitive) {
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
