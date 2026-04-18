<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-extended-contact-detail
 *
 * @description Contact details (including purpose and address) to assist a user in finding and communicating with the publisher.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-extended-contact-detail', fhirVersion: 'R4B')]
class ArtifactExtendedContactDetailExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive datatype DataType name 'ExtendedContactDetail' from R5 */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $datatype,
        /** @var CodeableConcept|null purpose The type of contact */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $purpose = null,
        /** @var array<HumanName> name Name of an individual to contact */
        #[FhirProperty(fhirType: 'HumanName', propertyKind: 'complex', isArray: true)]
        public array $name = [],
        /** @var array<ContactPoint> telecom Contact details (e.g.phone/fax/url) */
        #[FhirProperty(fhirType: 'ContactPoint', propertyKind: 'complex', isArray: true)]
        public array $telecom = [],
        /** @var Address|null address Address for the contact */
        #[FhirProperty(fhirType: 'Address', propertyKind: 'complex')]
        public ?Address $address = null,
        /** @var Reference|null organization This contact detail is handled/monitored by a specific organization */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $organization = null,
        /** @var Period|null period Period that this contact was valid for usage */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $period = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: '_datatype', value: $this->datatype);
        if ($this->purpose !== null) {
            $subExtensions[] = new Extension(url: 'purpose', value: $this->purpose);
        }
        foreach ($this->name as $v) {
            $subExtensions[] = new Extension(url: 'name', value: $v);
        }
        foreach ($this->telecom as $v) {
            $subExtensions[] = new Extension(url: 'telecom', value: $v);
        }
        if ($this->address !== null) {
            $subExtensions[] = new Extension(url: 'address', value: $this->address);
        }
        if ($this->organization !== null) {
            $subExtensions[] = new Extension(url: 'organization', value: $this->organization);
        }
        if ($this->period !== null) {
            $subExtensions[] = new Extension(url: 'period', value: $this->period);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-extended-contact-detail',
        );
    }

    /**
     * Reconstruct from an array of already-denormalized sub-extension objects.
     *
     * @param array<FHIRExtensionInterface> $subExtensions
     * @param string|null                   $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
    {
        $datatype     = null;
        $purpose      = null;
        $name         = [];
        $telecom      = [];
        $address      = null;
        $organization = null;
        $period       = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === '_datatype' && $ext->value instanceof StringPrimitive) {
                $datatype = $ext->value;
            }
            if ($extUrl === 'purpose' && $ext->value instanceof CodeableConcept) {
                $purpose = $ext->value;
            }
            if ($extUrl === 'name' && $ext->value instanceof HumanName) {
                $name[] = $ext->value;
            }
            if ($extUrl === 'telecom' && $ext->value instanceof ContactPoint) {
                $telecom[] = $ext->value;
            }
            if ($extUrl === 'address' && $ext->value instanceof Address) {
                $address = $ext->value;
            }
            if ($extUrl === 'organization' && $ext->value instanceof Reference) {
                $organization = $ext->value;
            }
            if ($extUrl === 'period' && $ext->value instanceof Period) {
                $period = $ext->value;
            }
        }

        if ($datatype === null) {
            throw new \InvalidArgumentException('Required sub-extension "_datatype" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($datatype, $purpose, $name, $telecom, $address, $organization, $period, $id);
    }
}
