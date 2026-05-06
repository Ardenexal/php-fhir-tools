<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ClaimResponse;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A claim detail. Either a simple (a product or service) or a 'group' of sub-details which are simple items.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.item.detail', fhirVersion: 'R5')]
class ClaimResponseItemDetail extends BackboneElement
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
        /** @var PositiveIntPrimitive|null detailSequence Claim detail instance identifier */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?PositiveIntPrimitive $detailSequence = null,
        /** @var array<Identifier> traceNumber Number for tracking */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $traceNumber = [],
        /** @var array<PositiveIntPrimitive> noteNumber Applicable note numbers */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
        public array $noteNumber = [],
        /** @var ClaimResponseItemReviewOutcome|null reviewOutcome Detail level adjudication results */
        #[FhirProperty(fhirType: 'unknown', propertyKind: 'complex')]
        public ?ClaimResponseItemReviewOutcome $reviewOutcome = null,
        /** @var array<ClaimResponseItemAdjudication> adjudication Detail level adjudication details */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ClaimResponse\ClaimResponseItemAdjudication',
        )]
        public array $adjudication = [],
        /** @var array<ClaimResponseItemDetailSubDetail> subDetail Adjudication for claim sub-details */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ClaimResponse\ClaimResponseItemDetailSubDetail',
        )]
        public array $subDetail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
