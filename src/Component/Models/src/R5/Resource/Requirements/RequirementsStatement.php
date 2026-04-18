<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Requirements;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ConformanceExpectationType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The actual statement of requirement, in markdown format.
 */
#[FHIRBackboneElement(parentResource: 'Requirements', elementPath: 'Requirements.statement', fhirVersion: 'R5')]
class RequirementsStatement extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var IdPrimitive|null key Key that identifies this statement */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?IdPrimitive $key = null,
        /** @var StringPrimitive|string|null label Short Human label for this statement */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $label = null,
        /** @var array<ConformanceExpectationType> conformance SHALL | SHOULD | MAY | SHOULD-NOT */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
        public array $conformance = [],
        /** @var bool|null conditionality Set to true if requirements statement is conditional */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $conditionality = null,
        /** @var MarkdownPrimitive|null requirement The actual requirement */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?MarkdownPrimitive $requirement = null,
        /** @var StringPrimitive|string|null derivedFrom Another statement this clarifies/restricts ([url#]key) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $derivedFrom = null,
        /** @var StringPrimitive|string|null parent A larger requirement that this requirement helps to refine and enable */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $parent = null,
        /** @var array<UrlPrimitive> satisfiedBy Design artifact that satisfies this requirement */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive', isArray: true)]
        public array $satisfiedBy = [],
        /** @var array<UrlPrimitive> reference External artifact (rule/document etc. that) created this requirement */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive', isArray: true)]
        public array $reference = [],
        /** @var array<Reference> source Who asked for this statement */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
