<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Immunization;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @description Educational material presented to the patient (or guardian) at the time of vaccine administration.
 */
#[FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.education', fhirVersion: 'R4')]
class ImmunizationEducation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null documentType Educational material document identifier */
        public StringPrimitive|string|null $documentType = null,
        /** @var UriPrimitive|null reference Educational material reference pointer */
        public ?UriPrimitive $reference = null,
        /** @var DateTimePrimitive|null publicationDate Educational material publication date */
        public ?DateTimePrimitive $publicationDate = null,
        /** @var DateTimePrimitive|null presentationDate Educational material presentation date */
        public ?DateTimePrimitive $presentationDate = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
