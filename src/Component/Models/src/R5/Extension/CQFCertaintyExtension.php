<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-certainty
 *
 * @description An assessment of certainty, confidence, or quality of the item on which it appears.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-certainty', fhirVersion: 'R5')]
class CQFCertaintyExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive|null description Textual description of certainty */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $description = null,
        /** @var array<Annotation> note Footnotes and/or explanatory notes */
        #[FhirProperty(fhirType: 'Annotation', propertyKind: 'complex', isArray: true)]
        public array $note = [],
        /** @var CodeableConcept|null type Aspect of certainty being rated */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null rating Assessment or judgement of the aspect */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $rating = null,
        /** @var StringPrimitive|null rater Who provided the rating */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $rater = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->description !== null) {
            $subExtensions[] = new Extension(url: 'description', value: $this->description);
        }
        foreach ($this->note as $v) {
            $subExtensions[] = new Extension(url: 'note', value: $v);
        }
        if ($this->type !== null) {
            $subExtensions[] = new Extension(url: 'type', value: $this->type);
        }
        if ($this->rating !== null) {
            $subExtensions[] = new Extension(url: 'rating', value: $this->rating);
        }
        if ($this->rater !== null) {
            $subExtensions[] = new Extension(url: 'rater', value: $this->rater);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-certainty',
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
        $note        = [];
        $type        = null;
        $rating      = null;
        $rater       = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'description' && $ext->value instanceof StringPrimitive) {
                $description = $ext->value;
            }
            if ($extUrl === 'note' && $ext->value instanceof Annotation) {
                $note[] = $ext->value;
            }
            if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
                $type = $ext->value;
            }
            if ($extUrl === 'rating' && $ext->value instanceof CodeableConcept) {
                $rating = $ext->value;
            }
            if ($extUrl === 'rater' && $ext->value instanceof StringPrimitive) {
                $rater = $ext->value;
            }
        }

        return new static($description, $note, $type, $rating, $rater, $id);
    }
}
