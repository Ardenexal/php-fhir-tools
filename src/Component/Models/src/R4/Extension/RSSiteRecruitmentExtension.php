<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;

/**
 * @author HL7 International / Biomedical Research and Regulation
 *
 * @see http://hl7.org/fhir/StructureDefinition/researchStudy-siteRecruitment
 *
 * @description Target and actual numbers of sites for a study.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/researchStudy-siteRecruitment', fhirVersion: 'R4')]
class RSSiteRecruitmentExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var UnsignedIntPrimitive|null targetNumber The desired number of sites */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public ?UnsignedIntPrimitive $targetNumber = null,
        /** @var UnsignedIntPrimitive|null actualNumber The actual number of sites */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public ?UnsignedIntPrimitive $actualNumber = null,
        /** @var MarkdownPrimitive|null eligibility Inclusion and exclusion criteria */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $eligibility = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->targetNumber !== null) {
            $subExtensions[] = new Extension(url: 'targetNumber', value: $this->targetNumber);
        }
        if ($this->actualNumber !== null) {
            $subExtensions[] = new Extension(url: 'actualNumber', value: $this->actualNumber);
        }
        if ($this->eligibility !== null) {
            $subExtensions[] = new Extension(url: 'eligibility', value: $this->eligibility);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/researchStudy-siteRecruitment',
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
        $targetNumber = null;
        $actualNumber = null;
        $eligibility  = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'targetNumber' && $ext->value instanceof UnsignedIntPrimitive) {
                $targetNumber = $ext->value;
            }
            if ($extUrl === 'actualNumber' && $ext->value instanceof UnsignedIntPrimitive) {
                $actualNumber = $ext->value;
            }
            if ($extUrl === 'eligibility' && $ext->value instanceof MarkdownPrimitive) {
                $eligibility = $ext->value;
            }
        }

        return new static($targetNumber, $actualNumber, $eligibility, $id);
    }
}
