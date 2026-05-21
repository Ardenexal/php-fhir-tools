<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/obligation
 *
 * @description When appearing on an element, documents obligations that apply to applications implementing that element.  When appearing at the root of a StructureDefinition, indicates obligations that apply to all listed elements within the extension.  When appearing on a type, indicates obligations that apply to the use of that specific type.
 *
 *   The obligations relate to application behaviour, not the content of the element itself in the resource instances that contain this element. See the [Obligation](obligations.html) page in the core specification for further detail.
 *
 *   The usage sub-extension allows for specific context dependent rules that link clinical, workflow or implementation context to obligations e.g. this is support for female patients.
 *
 *   Obligations apply to only data that 'fits' the semantics of the element and the overall instance. For example, a Composition, List, or other grouper may inherently filter what instances are 'in scope' and, in some cases, might even indicate the included instances themselves should be subsetted (i.e. filtering out elements or certain element repetitions)
 *
 *   Once the data that is semantically appropriate to share has been identified, it's subsequently filtered by 'what's allowed to be shared' based on regulation, consent, etc.
 *
 *    Obligations might apply to only a subset of the remaining elements based on any specified obligation 'filter'.
 *
 *    Of those, there may only be an obligation for a limited number of matching occurrences. Which occurrences are chosen is selected by the system adhering to the obligation.
 *
 *    Note that the result of this on elements with repetitions is that a variable number of results will be included based on all of the above rules, which may be zero or more in any given instance.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/obligation', fhirVersion: 'R5')]
class ObligationExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive|null name Short label for collection of obligations */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $name = null,
        /** @var array<CodePrimitive> code Composite code describing the nature of the obligation */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
        public array $code = [],
        /** @var array<StringPrimitive> elementId When the obligation is on the profile itself, not a particular element, a list of elements to which it applies */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $elementId = [],
        /** @var array<CanonicalPrimitive> actor Actor(s) this obligation applies to (all actors if none) */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
        public array $actor = [],
        /** @var MarkdownPrimitive|null documentation Documentation of the purpose or application of the obligation */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $documentation = null,
        /** @var array<UsageContext> usage Qualifies the usage - jurisdiction, gender, workflow status etc */
        #[FhirProperty(fhirType: 'UsageContext', propertyKind: 'complex', isArray: true)]
        public array $usage = [],
        /** @var StringPrimitive|null filter Limits obligation to some repeats by FHIRPath */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $filter = null,
        /** @var StringPrimitive|null filterDocumentation Describes the intent of the filter (short) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $filterDocumentation = null,
        /** @var PositiveIntPrimitive|null applicableNumber # of repetitions obligation applies to */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $applicableNumber = null,
        /** @var array<CanonicalPrimitive> process The obligation only applies when performing the indicated process */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
        public array $process = [],
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->name !== null) {
            $subExtensions[] = new Extension(url: 'name', value: $this->name);
        }
        foreach ($this->code as $v) {
            $subExtensions[] = new Extension(url: 'code', value: $v);
        }
        foreach ($this->elementId as $v) {
            $subExtensions[] = new Extension(url: 'elementId', value: $v);
        }
        foreach ($this->actor as $v) {
            $subExtensions[] = new Extension(url: 'actor', value: $v);
        }
        if ($this->documentation !== null) {
            $subExtensions[] = new Extension(url: 'documentation', value: $this->documentation);
        }
        foreach ($this->usage as $v) {
            $subExtensions[] = new Extension(url: 'usage', value: $v);
        }
        if ($this->filter !== null) {
            $subExtensions[] = new Extension(url: 'filter', value: $this->filter);
        }
        if ($this->filterDocumentation !== null) {
            $subExtensions[] = new Extension(url: 'filterDocumentation', value: $this->filterDocumentation);
        }
        if ($this->applicableNumber !== null) {
            $subExtensions[] = new Extension(url: 'applicable-number', value: $this->applicableNumber);
        }
        foreach ($this->process as $v) {
            $subExtensions[] = new Extension(url: 'process', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/obligation',
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
        $name                = null;
        $code                = [];
        $elementId           = [];
        $actor               = [];
        $documentation       = null;
        $usage               = [];
        $filter              = null;
        $filterDocumentation = null;
        $applicableNumber    = null;
        $process             = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'name' && $ext->value instanceof StringPrimitive) {
                $name = $ext->value;
            }
            if ($extUrl === 'code' && $ext->value instanceof CodePrimitive) {
                $code[] = $ext->value;
            }
            if ($extUrl === 'elementId' && $ext->value instanceof StringPrimitive) {
                $elementId[] = $ext->value;
            }
            if ($extUrl === 'actor' && $ext->value instanceof CanonicalPrimitive) {
                $actor[] = $ext->value;
            }
            if ($extUrl === 'documentation' && $ext->value instanceof MarkdownPrimitive) {
                $documentation = $ext->value;
            }
            if ($extUrl === 'usage' && $ext->value instanceof UsageContext) {
                $usage[] = $ext->value;
            }
            if ($extUrl === 'filter' && $ext->value instanceof StringPrimitive) {
                $filter = $ext->value;
            }
            if ($extUrl === 'filterDocumentation' && $ext->value instanceof StringPrimitive) {
                $filterDocumentation = $ext->value;
            }
            if ($extUrl === 'applicable-number' && $ext->value instanceof PositiveIntPrimitive) {
                $applicableNumber = $ext->value;
            }
            if ($extUrl === 'process' && $ext->value instanceof CanonicalPrimitive) {
                $process[] = $ext->value;
            }
        }

        return new static($name, $code, $elementId, $actor, $documentation, $usage, $filter, $filterDocumentation, $applicableNumber, $process, $id);
    }
}
