<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\CapabilityStatement;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\DocumentModeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A document definition.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.document', fhirVersion: 'R5')]
class CapabilityStatementDocument extends BackboneElement
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
        /** @var DocumentModeType|null mode producer | consumer */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?DocumentModeType $mode = null,
        /** @var MarkdownPrimitive|null documentation Description of document support */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $documentation = null,
        /** @var CanonicalPrimitive|null profile Constraint on the resources used in the document */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?CanonicalPrimitive $profile = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
