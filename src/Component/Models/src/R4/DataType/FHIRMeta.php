<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRElement;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Meta
 *
 * @description The metadata about a resource. This is content in the resource that is maintained by the infrastructure. Changes to the content might not always be associated with version changes to the resource.
 */
#[FHIRComplexType(typeName: 'Meta', fhirVersion: 'R4')]
class FHIRMeta extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRId|null versionId Version specific identifier */
        public ?FHIRId $versionId = null,
        /** @var FHIRInstant|null lastUpdated When the resource version last changed */
        public ?FHIRInstant $lastUpdated = null,
        /** @var FHIRUri|null source Identifies where the resource comes from */
        public ?FHIRUri $source = null,
        /** @var array<FHIRCanonical> profile Profiles this resource claims to conform to */
        public array $profile = [],
        /** @var array<FHIRCoding> security Security Labels applied to this resource */
        public array $security = [],
        /** @var array<FHIRCoding> tag Tags applied to this resource */
        public array $tag = [],
    ) {
        parent::__construct($id, $extension);
    }
}
