<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive;

/**
 * @author HL7 International / Biomedical Research and Regulation
 *
 * @see http://hl7.org/fhir/StructureDefinition/researchStudy-interventionalistRecruitment
 *
 * @description Target and actual numbers of interventionalists for a study.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/researchStudy-interventionalistRecruitment', fhirVersion: 'R4B')]
class RSInterventionalistRecruitmentExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var UnsignedIntPrimitive|null targetNumber The desired number of interventionalists */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public ?UnsignedIntPrimitive $targetNumber = null,
        /** @var UnsignedIntPrimitive|null actualNumber The actual number of interventionalists */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public ?UnsignedIntPrimitive $actualNumber = null,
        /** @var MarkdownPrimitive|null description A human readable description */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
        /** @var Reference|null eligibilityCriteria Inclusion and exclusion criteria */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $eligibilityCriteria = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->targetNumber !== null) {
            $subExtensions[] = new Extension(url: 'targetNumber', value: $this->targetNumber);
        }
        if ($this->actualNumber !== null) {
            $subExtensions[] = new Extension(url: 'actualNumber', value: $this->actualNumber);
        }
        if ($this->description !== null) {
            $subExtensions[] = new Extension(url: 'description', value: $this->description);
        }
        if ($this->eligibilityCriteria !== null) {
            $subExtensions[] = new Extension(url: 'eligibilityCriteria', value: $this->eligibilityCriteria);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/researchStudy-interventionalistRecruitment',
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
        $targetNumber        = null;
        $actualNumber        = null;
        $description         = null;
        $eligibilityCriteria = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'targetNumber' && $ext->value instanceof UnsignedIntPrimitive) {
                $targetNumber = $ext->value;
            }
            if ($extUrl === 'actualNumber' && $ext->value instanceof UnsignedIntPrimitive) {
                $actualNumber = $ext->value;
            }
            if ($extUrl === 'description' && $ext->value instanceof MarkdownPrimitive) {
                $description = $ext->value;
            }
            if ($extUrl === 'eligibilityCriteria' && $ext->value instanceof Reference) {
                $eligibilityCriteria = $ext->value;
            }
        }

        return new static($targetNumber, $actualNumber, $description, $eligibilityCriteria, $id);
    }
}
