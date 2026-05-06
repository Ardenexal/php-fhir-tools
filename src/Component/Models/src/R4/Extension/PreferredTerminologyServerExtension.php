<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/preferredTerminologyServer
 *
 * @description Indicates the terminology server(s) that are known to be capable of returning and potentially expanding the value set(s) associated with the resource or a particular portion of the resource (depending on where the extension appears).  If a full URL is not provided AND the requested query is a terminology operation (e.g. $lookup or $expand) the client SHOULD execute the operation against (one of) the preferredTerminologyServer(s) rather than the local repository. Systems SHOULD evaluate value sets using terminology servers as follows:  First, try any terminology servers declared on the element in question.  If there are more then one, try them in the order they appear.  Then try any servers that appear on ancestor elements in order of closest ancestor up to any on the resource root  If there are no declared servers or none of the ones listed provide a useful response, the form filler may then try any of the typical servers it would normally use.  A 'useful response' means a response that provides a valid (though potentially empty) expansion.  Clients MAY wish to log/report errors returned by terminology servers.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/preferredTerminologyServer', fhirVersion: 'R4')]
class PreferredTerminologyServerExtension extends Extension
{
    public function __construct(
        /** @var UrlPrimitive|null valueUrl Value of extension */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive')]
        public ?UrlPrimitive $valueUrl = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/preferredTerminologyServer',
            value: $this->valueUrl,
        );
    }
}
