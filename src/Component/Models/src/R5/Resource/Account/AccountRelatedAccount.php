<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Account;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Other associated accounts related to this account.
 */
#[FHIRBackboneElement(parentResource: 'Account', elementPath: 'Account.relatedAccount', fhirVersion: 'R5')]
class AccountRelatedAccount extends BackboneElement
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
        /** @var CodeableConcept|null relationship Relationship of the associated Account */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $relationship = null,
        /** @var Reference|null account Reference to an associated Account */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $account = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
