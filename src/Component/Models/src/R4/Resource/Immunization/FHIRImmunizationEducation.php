<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;

/**
 * @description Educational material presented to the patient (or guardian) at the time of vaccine administration.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.education', fhirVersion: 'R4')]
class FHIRImmunizationEducation extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null documentType Educational material document identifier */
        public FHIRString|string|null $documentType = null,
        /** @var FHIRUri|null reference Educational material reference pointer */
        public ?FHIRUri $reference = null,
        /** @var FHIRDateTime|null publicationDate Educational material publication date */
        public ?FHIRDateTime $publicationDate = null,
        /** @var FHIRDateTime|null presentationDate Educational material presentation date */
        public ?FHIRDateTime $presentationDate = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
