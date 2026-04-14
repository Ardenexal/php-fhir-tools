<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\MessageHeader;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ResponseTypeType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about the message that this message is a response to.  Only present if this message is a response.
 */
#[FHIRBackboneElement(parentResource: 'MessageHeader', elementPath: 'MessageHeader.response', fhirVersion: 'R5')]
class MessageHeaderResponse extends BackboneElement
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
        /** @var Identifier|null identifier Bundle.identifier of original message */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Identifier $identifier = null,
        /** @var ResponseTypeType|null code ok | transient-error | fatal-error */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?ResponseTypeType $code = null,
        /** @var Reference|null details Specific list of hints/warnings/errors */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $details = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
