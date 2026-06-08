<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-supportingEvidence
 *
 * @description Specifies the result of a supporting evidence expression in the measure population. The result of the expression is represented in an extension, using the same mapping as specified in Using CQL With FHIR, with the exception that tuples and lists are represented in extensions, rather than mapped to the Parameters resource.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-supportingEvidence', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'MeasureReport.group.population')]
class SupportingEvidenceExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodePrimitive name The name of the supporting evidence definition */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $name,
        /** @var StringPrimitive|null description The description of the supporting evidence definition, if provided */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $description = null,
        /** @var CodeableConcept|null code The code of the supporting evidence definition, if provided */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $code = null,
        /** @var array<Base64BinaryPrimitive> valueSlice The supporting evidence value */
        #[FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive', isArray: true)]
        public array $valueSlice = [],
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'name', value: $this->name);
        if ($this->description !== null) {
            $subExtensions[] = new Extension(url: 'description', value: $this->description);
        }
        if ($this->code !== null) {
            $subExtensions[] = new Extension(url: 'code', value: $this->code);
        }
        foreach ($this->valueSlice as $v) {
            $subExtensions[] = new Extension(url: 'value', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-supportingEvidence',
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
        $name        = null;
        $description = null;
        $code        = null;
        $valueSlice  = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'name' && $ext->value instanceof CodePrimitive) {
                $name = $ext->value;
            }
            if ($extUrl === 'description' && $ext->value instanceof StringPrimitive) {
                $description = $ext->value;
            }
            if ($extUrl === 'code' && $ext->value instanceof CodeableConcept) {
                $code = $ext->value;
            }
            if ($extUrl === 'value' && $ext->value instanceof Base64BinaryPrimitive) {
                $valueSlice[] = $ext->value;
            }
        }

        if ($name === null) {
            throw new \InvalidArgumentException('Required sub-extension "name" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new self($name, $description, $code, $valueSlice, $id);
    }
}
