<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/extended-contact-availability
 *
 * @description The details provided in this contact may be used according to the attached availability guidelines.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/extended-contact-availability', fhirVersion: 'R4B')]
class ExtendedContactAvailabilityExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive datatype DataType name 'Availability' from R5 */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $datatype,
        /** @var array<Base64BinaryPrimitive> availableTime Times the {item} is available */
        #[FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive', isArray: true)]
        public array $availableTime = [],
        /** @var array<Base64BinaryPrimitive> notAvailableTime Not available during this time due to provided reason */
        #[FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive', isArray: true)]
        public array $notAvailableTime = [],
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: '_datatype', value: $this->datatype);
        foreach ($this->availableTime as $v) {
            $subExtensions[] = new Extension(url: 'availableTime', value: $v);
        }
        foreach ($this->notAvailableTime as $v) {
            $subExtensions[] = new Extension(url: 'notAvailableTime', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/extended-contact-availability',
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
        $datatype         = null;
        $availableTime    = [];
        $notAvailableTime = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === '_datatype' && $ext->value instanceof StringPrimitive) {
                $datatype = $ext->value;
            }
            if ($extUrl === 'availableTime' && $ext->value instanceof Base64BinaryPrimitive) {
                $availableTime[] = $ext->value;
            }
            if ($extUrl === 'notAvailableTime' && $ext->value instanceof Base64BinaryPrimitive) {
                $notAvailableTime[] = $ext->value;
            }
        }

        if ($datatype === null) {
            throw new \InvalidArgumentException('Required sub-extension "_datatype" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($datatype, $availableTime, $notAvailableTime, $id);
    }
}
