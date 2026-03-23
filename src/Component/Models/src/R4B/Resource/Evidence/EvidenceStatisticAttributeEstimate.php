<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Evidence;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @description A statistical attribute of the statistic such as a measure of heterogeneity.
 */
#[FHIRBackboneElement(parentResource: 'Evidence', elementPath: 'Evidence.statistic.attributeEstimate', fhirVersion: 'R4B')]
class EvidenceStatisticAttributeEstimate extends BackboneElement
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
        /** @var StringPrimitive|string|null description Textual description of the attribute estimate */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
        /** @var array<Annotation> note Footnote or explanatory note about the estimate */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Annotation',
        )]
        public array $note = [],
        /** @var CodeableConcept|null type The type of attribute estimate, eg confidence interval or p value */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var Quantity|null quantity The singular quantity of the attribute estimate, for attribute estimates represented as single values; also used to report unit of measure */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $quantity = null,
        /** @var numeric-string|null level Level of confidence interval, eg 0.95 for 95% confidence interval */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $level = null,
        /** @var Range|null range Lower and upper bound values of the attribute estimate */
        #[FhirProperty(fhirType: 'Range', propertyKind: 'complex')]
        public ?Range $range = null,
        /** @var array<EvidenceStatisticAttributeEstimate> attributeEstimate A nested attribute estimate; which is the attribute estimate of an attribute estimate */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Evidence\EvidenceStatisticAttributeEstimate',
        )]
        public array $attributeEstimate = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
