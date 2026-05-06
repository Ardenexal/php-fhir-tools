<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/servicerequest-specimenSuggestion
 *
 * @description This attribute enables the requester of this service to indicate they would like a certain specimen to be used, but if that is not available/appropriate to be used whether to have another specimen drawn, or that this test is not performed. When a specimen is referenced it is expected to be used. If it cannot be used, the decision to collect another specimen or not perform the test must be based on established policies or determined in some other agreed to way with the requester.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/servicerequest-specimenSuggestion', fhirVersion: 'R5')]
class SRSpecimenSuggestionExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableReference reference Extension */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public CodeableReference $reference,
        /** @var CodePrimitive|null fallBackAction Extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $fallBackAction = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'reference', value: $this->reference);
        if ($this->fallBackAction !== null) {
            $subExtensions[] = new Extension(url: 'fallBackAction', value: $this->fallBackAction);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/servicerequest-specimenSuggestion',
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
        $reference      = null;
        $fallBackAction = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'reference' && $ext->value instanceof CodeableReference) {
                $reference = $ext->value;
            }
            if ($extUrl === 'fallBackAction' && $ext->value instanceof CodePrimitive) {
                $fallBackAction = $ext->value;
            }
        }

        if ($reference === null) {
            throw new \InvalidArgumentException('Required sub-extension "reference" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($reference, $fallBackAction, $id);
    }
}
