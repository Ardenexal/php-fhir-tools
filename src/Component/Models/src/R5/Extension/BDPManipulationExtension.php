<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-manipulation
 *
 * @description Extension for manipulation of a biologically derived product.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-manipulation', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'BiologicallyDerivedProduct')]
class BDPManipulationExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive|null description Description of manipulation */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $description = null,
        /** @var array<CodeableReference> procedure Processing procedure */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex', isArray: true)]
        public array $procedure = [],
        /** @var DateTimePrimitive|null timeX Time of manipulation */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $timeX = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->description !== null) {
            $subExtensions[] = new Extension(url: 'description', value: $this->description);
        }
        foreach ($this->procedure as $v) {
            $subExtensions[] = new Extension(url: 'procedure', value: $v);
        }
        if ($this->timeX !== null) {
            $subExtensions[] = new Extension(url: 'time[x]', value: $this->timeX);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-manipulation',
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
        $description = null;
        $procedure   = [];
        $timeX       = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'description' && $ext->value instanceof StringPrimitive) {
                $description = $ext->value;
            }
            if ($extUrl === 'procedure' && $ext->value instanceof CodeableReference) {
                $procedure[] = $ext->value;
            }
            if ($extUrl === 'time[x]' && $ext->value instanceof DateTimePrimitive) {
                $timeX = $ext->value;
            }
        }

        return new self($description, $procedure, $timeX, $id);
    }
}
