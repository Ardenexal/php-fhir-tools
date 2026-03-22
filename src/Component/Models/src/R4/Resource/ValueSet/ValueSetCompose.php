<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;

/**
 * @description A set of criteria that define the contents of the value set by including or excluding codes selected from the specified code system(s) that the value set draws from. This is also known as the Content Logical Definition (CLD).
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.compose', fhirVersion: 'R4')]
class ValueSetCompose extends BackboneElement
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
        /** @var DatePrimitive|null lockedDate Fixed date for references with no specified version (transitive) */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
        public ?DatePrimitive $lockedDate = null,
        /** @var bool|null inactive Whether inactive codes are in the value set */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $inactive = null,
        /** @var array<ValueSetComposeInclude> include Include one or more codes from a code system or other value set(s) */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            isRequired: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet\ValueSetComposeInclude',
        )]
        public array $include = [],
        /** @var array<ValueSetComposeInclude> exclude Explicitly exclude codes from a code system or other value sets */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet\ValueSetComposeInclude',
        )]
        public array $exclude = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
