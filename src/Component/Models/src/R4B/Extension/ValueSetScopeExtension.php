<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-scope
 *
 * @description Description of the semantic space the Value Set Expansion is intended to cover and should further clarify the text in ValueSet.description.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-scope', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'ValueSet')]
class ValueSetScopeExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive|null inclusionCriteria Criteria describing which concepts or codes should be included and why */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $inclusionCriteria = null,
        /** @var StringPrimitive|null exclusionCriteria Criteria describing which concepts or codes should be excluded and why */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $exclusionCriteria = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->inclusionCriteria !== null) {
            $subExtensions[] = new Extension(url: 'inclusionCriteria', value: $this->inclusionCriteria);
        }
        if ($this->exclusionCriteria !== null) {
            $subExtensions[] = new Extension(url: 'exclusionCriteria', value: $this->exclusionCriteria);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-scope',
        );
    }

    /**
     * Reconstruct from an array of already-denormalized sub-extension objects.
     *
     * @param array<Extension> $subExtensions
     * @param string|null      $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): self
    {
        $inclusionCriteria = null;
        $exclusionCriteria = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'inclusionCriteria' && $ext->value instanceof StringPrimitive) {
                $inclusionCriteria = $ext->value;
            }
            if ($extUrl === 'exclusionCriteria' && $ext->value instanceof StringPrimitive) {
                $exclusionCriteria = $ext->value;
            }
        }

        return new self($inclusionCriteria, $exclusionCriteria, $id);
    }
}
