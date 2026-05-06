<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-processing
 *
 * @description Extension for processing of a biologically derived product.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-processing', fhirVersion: 'R4B')]
class BDPProcessingExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive|null description Processing of description */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $description = null,
        /** @var CodeableReference|null procedure Procesing procedure */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $procedure = null,
        /** @var Reference|null additive Substance added during processing */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $additive = null,
        /** @var DateTimePrimitive|null timeX Time of processing */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $timeX = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->description !== null) {
            $subExtensions[] = new Extension(url: 'description', value: $this->description);
        }
        if ($this->procedure !== null) {
            $subExtensions[] = new Extension(url: 'procedure', value: $this->procedure);
        }
        if ($this->additive !== null) {
            $subExtensions[] = new Extension(url: 'additive', value: $this->additive);
        }
        if ($this->timeX !== null) {
            $subExtensions[] = new Extension(url: 'time[x]', value: $this->timeX);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/biologicallyderivedproduct-processing',
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
        $description = null;
        $procedure   = null;
        $additive    = null;
        $timeX       = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'description' && $ext->value instanceof StringPrimitive) {
                $description = $ext->value;
            }
            if ($extUrl === 'procedure' && $ext->value instanceof CodeableReference) {
                $procedure = $ext->value;
            }
            if ($extUrl === 'additive' && $ext->value instanceof Reference) {
                $additive = $ext->value;
            }
            if ($extUrl === 'time[x]' && $ext->value instanceof DateTimePrimitive) {
                $timeX = $ext->value;
            }
        }

        return new static($description, $procedure, $additive, $timeX, $id);
    }
}
