<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\OidPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UuidPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ElementDefinition
 *
 * @description Captures constraints on each element within the resource, profile, or extension.
 */
#[FHIRBackboneElement(parentResource: 'ElementDefinition', elementPath: 'ElementDefinition', fhirVersion: 'R4')]
class ElementDefinition extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null path Path of the element in the hierarchy of elements */
        #[NotBlank]
        public StringPrimitive|string|null $path = null,
        /** @var array<PropertyRepresentationType> representation xmlAttr | xmlText | typeAttr | cdaText | xhtml */
        public array $representation = [],
        /** @var StringPrimitive|string|null sliceName Name for this particular element (in a set of slices) */
        public StringPrimitive|string|null $sliceName = null,
        /** @var bool|null sliceIsConstraining If this slice definition constrains an inherited slice definition (or not) */
        public ?bool $sliceIsConstraining = null,
        /** @var StringPrimitive|string|null label Name for element to display with or prompt for element */
        public StringPrimitive|string|null $label = null,
        /** @var array<Coding> code Corresponding codes in terminologies */
        public array $code = [],
        /** @var ElementDefinitionSlicing|null slicing This element is sliced - slices follow */
        public ?ElementDefinitionSlicing $slicing = null,
        /** @var StringPrimitive|string|null short Concise definition for space-constrained presentation */
        public StringPrimitive|string|null $short = null,
        /** @var MarkdownPrimitive|null definition Full formal definition as narrative text */
        public ?MarkdownPrimitive $definition = null,
        /** @var MarkdownPrimitive|null comment Comments about the use of this element */
        public ?MarkdownPrimitive $comment = null,
        /** @var MarkdownPrimitive|null requirements Why this resource has been created */
        public ?MarkdownPrimitive $requirements = null,
        /** @var array<StringPrimitive|string> alias Other names */
        public array $alias = [],
        /** @var UnsignedIntPrimitive|null min Minimum Cardinality */
        public ?UnsignedIntPrimitive $min = null,
        /** @var StringPrimitive|string|null max Maximum Cardinality (a number or *) */
        public StringPrimitive|string|null $max = null,
        /** @var ElementDefinitionBase|null base Base definition information for tools */
        public ?ElementDefinitionBase $base = null,
        /** @var UriPrimitive|null contentReference Reference to definition of content for the element */
        public ?UriPrimitive $contentReference = null,
        /** @var array<ElementDefinitionType> type Data type and Profile for this element */
        public array $type = [],
        /** @var Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|float|IdPrimitive|InstantPrimitive|int|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|string|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|Reference|SampledData|Signature|Timing|ContactDetail|Contributor|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Dosage|Meta|null defaultValueX Specified value if missing from instance */
        public Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|float|IdPrimitive|InstantPrimitive|int|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|string|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|Reference|SampledData|Signature|Timing|ContactDetail|Contributor|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Dosage|Meta|null $defaultValueX = null,
        /** @var MarkdownPrimitive|null meaningWhenMissing Implicit meaning when this element is missing */
        public ?MarkdownPrimitive $meaningWhenMissing = null,
        /** @var StringPrimitive|string|null orderMeaning What the order of the elements means */
        public StringPrimitive|string|null $orderMeaning = null,
        /** @var Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|float|IdPrimitive|InstantPrimitive|int|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|string|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|Reference|SampledData|Signature|Timing|ContactDetail|Contributor|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Dosage|Meta|null fixedX Value must be exactly this */
        public Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|float|IdPrimitive|InstantPrimitive|int|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|string|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|Reference|SampledData|Signature|Timing|ContactDetail|Contributor|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Dosage|Meta|null $fixedX = null,
        /** @var Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|float|IdPrimitive|InstantPrimitive|int|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|string|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|Reference|SampledData|Signature|Timing|ContactDetail|Contributor|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Dosage|Meta|null patternX Value must have at least these property values */
        public Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|float|IdPrimitive|InstantPrimitive|int|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|string|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|Reference|SampledData|Signature|Timing|ContactDetail|Contributor|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Dosage|Meta|null $patternX = null,
        /** @var array<ElementDefinitionExample> example Example value (as defined for type) */
        public array $example = [],
        /** @var DatePrimitive|DateTimePrimitive|InstantPrimitive|TimePrimitive|float|int|PositiveIntPrimitive|UnsignedIntPrimitive|Quantity|null minValueX Minimum Allowed Value (for some types) */
        public DatePrimitive|DateTimePrimitive|InstantPrimitive|TimePrimitive|float|int|PositiveIntPrimitive|UnsignedIntPrimitive|Quantity|null $minValueX = null,
        /** @var DatePrimitive|DateTimePrimitive|InstantPrimitive|TimePrimitive|float|int|PositiveIntPrimitive|UnsignedIntPrimitive|Quantity|null maxValueX Maximum Allowed Value (for some types) */
        public DatePrimitive|DateTimePrimitive|InstantPrimitive|TimePrimitive|float|int|PositiveIntPrimitive|UnsignedIntPrimitive|Quantity|null $maxValueX = null,
        /** @var int|null maxLength Max length for strings */
        public ?int $maxLength = null,
        /** @var array<IdPrimitive> condition Reference to invariant about presence */
        public array $condition = [],
        /** @var array<ElementDefinitionConstraint> constraint Condition that must evaluate to true */
        public array $constraint = [],
        /** @var bool|null mustSupport If the element must be supported */
        public ?bool $mustSupport = null,
        /** @var bool|null isModifier If this modifies the meaning of other elements */
        public ?bool $isModifier = null,
        /** @var StringPrimitive|string|null isModifierReason Reason that this element is marked as a modifier */
        public StringPrimitive|string|null $isModifierReason = null,
        /** @var bool|null isSummary Include when _summary = true? */
        public ?bool $isSummary = null,
        /** @var ElementDefinitionBinding|null binding ValueSet details if this is coded */
        public ?ElementDefinitionBinding $binding = null,
        /** @var array<ElementDefinitionMapping> mapping Map element to another set of definitions */
        public array $mapping = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
