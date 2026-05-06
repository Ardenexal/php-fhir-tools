<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Account;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Money;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The calculated account balances - these are calculated and processed by the finance system.
 *
 * The balances with a `term` that is not current are usually generated/updated by an invoicing or similar process.
 */
#[FHIRBackboneElement(parentResource: 'Account', elementPath: 'Account.balance', fhirVersion: 'R5')]
class AccountBalance extends BackboneElement
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
        /** @var CodeableConcept|null aggregate Who is expected to pay this part of the balance */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $aggregate = null,
        /** @var CodeableConcept|null term current | 30 | 60 | 90 | 120 */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $term = null,
        /** @var bool|null estimate Estimated balance */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $estimate = null,
        /** @var Money|null amount Calculated amount */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Money $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
