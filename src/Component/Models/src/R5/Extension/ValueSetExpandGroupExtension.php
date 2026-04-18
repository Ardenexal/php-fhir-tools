<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / Terminology Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-expand-group
 *
 * @description This extension declares a group of concepts that is generated into the ValueSet.expansion.contains hierarchy when the expansion is generated for a UI. THere is no inherent assigned meaning to the hierarchy; it is used to help the user navigate the concepts. Each group has a display and/or a code, and a list of members, which are either concepts in the value set, or other groups (by code).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-expand-group', fhirVersion: 'R5')]
class ValueSetExpandGroupExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodePrimitive|null code Underlying code from the system */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $code = null,
        /** @var StringPrimitive|null display Display for the group */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $display = null,
        /** @var array<CodePrimitive> member Codes or other groups in this group */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
        public array $member = [],
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->code !== null) {
            $subExtensions[] = new Extension(url: 'code', value: $this->code);
        }
        if ($this->display !== null) {
            $subExtensions[] = new Extension(url: 'display', value: $this->display);
        }
        foreach ($this->member as $v) {
            $subExtensions[] = new Extension(url: 'member', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-expand-group',
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
        $code    = null;
        $display = null;
        $member  = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'code' && $ext->value instanceof CodePrimitive) {
                $code = $ext->value;
            }
            if ($extUrl === 'display' && $ext->value instanceof StringPrimitive) {
                $display = $ext->value;
            }
            if ($extUrl === 'member' && $ext->value instanceof CodePrimitive) {
                $member[] = $ext->value;
            }
        }

        return new static($code, $display, $member, $id);
    }
}
