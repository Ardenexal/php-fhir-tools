<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Meta
 *
 * @description The metadata about a resource. This is content in the resource that is maintained by the infrastructure. Changes to the content might not always be associated with version changes to the resource.
 */
#[FHIRComplexType(typeName: 'Meta', fhirVersion: 'R4')]
class Meta extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var IdPrimitive|null versionId Version specific identifier */
        public ?IdPrimitive $versionId = null,
        /** @var InstantPrimitive|null lastUpdated When the resource version last changed */
        public ?InstantPrimitive $lastUpdated = null,
        /** @var UriPrimitive|null source Identifies where the resource comes from */
        public ?UriPrimitive $source = null,
        /** @var array<CanonicalPrimitive> profile Profiles this resource claims to conform to */
        public array $profile = [],
        /** @var array<Coding> security Security Labels applied to this resource */
        public array $security = [],
        /** @var array<Coding> tag Tags applied to this resource */
        public array $tag = [],
    ) {
        parent::__construct($id, $extension);
    }
}
