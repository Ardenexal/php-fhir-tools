<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/intended-context
 *
 * @description Indicates where the extension is intended to be used, like StructureDefinition.context, but is merely guidance. E.g An extension might be defined for use on any Element, but an author can use this indicate that the extension is intended to be used on a few particular DataTypes.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/intended-context', fhirVersion: 'R4B')]
class IntendedContextExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodePrimitive|null type fhirpath | element | extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $type = null,
        /** @var StringPrimitive|null expression Where the extension can be used in instances */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $expression = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->type !== null) {
            $subExtensions[] = new Extension(url: 'type', value: $this->type);
        }
        if ($this->expression !== null) {
            $subExtensions[] = new Extension(url: 'expression', value: $this->expression);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/intended-context',
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
        $type       = null;
        $expression = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'type' && $ext->value instanceof CodePrimitive) {
                $type = $ext->value;
            }
            if ($extUrl === 'expression' && $ext->value instanceof StringPrimitive) {
                $expression = $ext->value;
            }
        }

        return new static($type, $expression, $id);
    }
}
