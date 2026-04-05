<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Citation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @description Provenance and copyright of classification.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.classification.whoClassified', fhirVersion: 'R4B')]
class CitationCitedArtifactClassificationWhoClassified extends BackboneElement
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
        /** @var Reference|null person Person who created the classification */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $person = null,
        /** @var Reference|null organization Organization who created the classification */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $organization = null,
        /** @var Reference|null publisher The publisher of the classification, not the publisher of the article or artifact being cited */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $publisher = null,
        /** @var StringPrimitive|string|null classifierCopyright Rights management statement for the classification */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $classifierCopyright = null,
        /** @var bool|null freeToShare Acceptable to re-use the classification */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $freeToShare = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
