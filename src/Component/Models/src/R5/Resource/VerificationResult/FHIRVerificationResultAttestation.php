<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSignature;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description Information about the entity attesting to information.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'VerificationResult', elementPath: 'VerificationResult.attestation', fhirVersion: 'R5')]
class FHIRVerificationResultAttestation extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null who The individual or organization attesting to information */
        public ?FHIRReference $who = null,
        /** @var FHIRReference|null onBehalfOf When the who is asserting on behalf of another (organization or individual) */
        public ?FHIRReference $onBehalfOf = null,
        /** @var FHIRCodeableConcept|null communicationMethod The method by which attested information was submitted/retrieved */
        public ?FHIRCodeableConcept $communicationMethod = null,
        /** @var FHIRDate|null date The date the information was attested to */
        public ?FHIRDate $date = null,
        /** @var FHIRString|string|null sourceIdentityCertificate A digital identity certificate associated with the attestation source */
        public FHIRString|string|null $sourceIdentityCertificate = null,
        /** @var FHIRString|string|null proxyIdentityCertificate A digital identity certificate associated with the proxy entity submitting attested information on behalf of the attestation source */
        public FHIRString|string|null $proxyIdentityCertificate = null,
        /** @var FHIRSignature|null proxySignature Proxy signature (digital or image) */
        public ?FHIRSignature $proxySignature = null,
        /** @var FHIRSignature|null sourceSignature Attester signature (digital or image) */
        public ?FHIRSignature $sourceSignature = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
