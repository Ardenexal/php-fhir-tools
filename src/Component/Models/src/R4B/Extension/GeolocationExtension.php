<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/geolocation
 *
 * @description An absolute geographic location for the address, expressed using the WGS84 datum (This is the same co-ordinate system used in KML).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/geolocation', fhirVersion: 'R4B')]
class GeolocationExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var string latitude Latitude with WGS84 datum */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public string $latitude,
        /** @var string longitude Longitude with WGS84 datum */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public string $longitude,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'latitude', value: $this->latitude);
        $subExtensions[] = new Extension(url: 'longitude', value: $this->longitude);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/geolocation',
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
        $latitude  = null;
        $longitude = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'latitude' && is_string($ext->value)) {
                $latitude = $ext->value;
            }
            if ($extUrl === 'longitude' && is_string($ext->value)) {
                $longitude = $ext->value;
            }
        }

        if ($latitude === null) {
            throw new \InvalidArgumentException('Required sub-extension "latitude" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($longitude === null) {
            throw new \InvalidArgumentException('Required sub-extension "longitude" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($latitude, $longitude, $id);
    }
}
