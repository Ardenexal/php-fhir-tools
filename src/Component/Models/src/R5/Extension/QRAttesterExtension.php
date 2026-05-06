<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/questionnaireresponse-attester
 *
 * @description Allows capturing the individual(s) who attest to the accuracy of the information within the QuestionnaireResponse.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaireresponse-attester', fhirVersion: 'R5')]
class QRAttesterExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodePrimitive mode Extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $mode,
        /** @var DateTimePrimitive|null time Extension */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $time = null,
        /** @var Reference|null party Extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $party = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'mode', value: $this->mode);
        if ($this->time !== null) {
            $subExtensions[] = new Extension(url: 'time', value: $this->time);
        }
        if ($this->party !== null) {
            $subExtensions[] = new Extension(url: 'party', value: $this->party);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/questionnaireresponse-attester',
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
        $mode  = null;
        $time  = null;
        $party = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'mode' && $ext->value instanceof CodePrimitive) {
                $mode = $ext->value;
            }
            if ($extUrl === 'time' && $ext->value instanceof DateTimePrimitive) {
                $time = $ext->value;
            }
            if ($extUrl === 'party' && $ext->value instanceof Reference) {
                $party = $ext->value;
            }
        }

        if ($mode === null) {
            throw new \InvalidArgumentException('Required sub-extension "mode" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($mode, $time, $party, $id);
    }
}
