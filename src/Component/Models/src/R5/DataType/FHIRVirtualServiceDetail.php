<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/VirtualServiceDetail
 *
 * @description Virtual Service Contact Details.
 */
#[FHIRComplexType(typeName: 'VirtualServiceDetail', fhirVersion: 'R5')]
class FHIRVirtualServiceDetail extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRCoding|null channelType Channel Type */
        public ?\FHIRCoding $channelType = null,
        /** @var FHIRUrl|FHIRString|string|FHIRContactPoint|FHIRExtendedContactDetail|null addressX Contact address/number */
        public \FHIRUrl|\FHIRString|string|\FHIRContactPoint|\FHIRExtendedContactDetail|null $addressX = null,
        /** @var array<FHIRUrl> additionalInfo Address to see alternative connection details */
        public array $additionalInfo = [],
        /** @var FHIRPositiveInt|null maxParticipants Maximum number of participants supported by the virtual service */
        public ?\FHIRPositiveInt $maxParticipants = null,
        /** @var FHIRString|string|null sessionKey Session Key required by the virtual service */
        public \FHIRString|string|null $sessionKey = null,
    ) {
        parent::__construct($id, $extension);
    }
}
