<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\DocumentReference;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The document and format referenced. There may be multiple content element repetitions, each with a different format.
 */
#[FHIRBackboneElement(parentResource: 'DocumentReference', elementPath: 'DocumentReference.content', fhirVersion: 'R4B')]
class DocumentReferenceContent extends BackboneElement
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
        /** @var Attachment|null attachment Where to access the document */
        #[FhirProperty(fhirType: 'Attachment', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Attachment $attachment = null,
        /** @var Coding|null format Format/content rules for the document */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public ?Coding $format = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
