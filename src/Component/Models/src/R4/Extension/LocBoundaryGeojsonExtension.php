<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Patient Administration
 * @see http://hl7.org/fhir/StructureDefinition/location-boundary-geojson
 * @description A boundary shape that represents the outside edge of the location (in GeoJSON format) This shape may have holes, and disconnected shapes.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/location-boundary-geojson', fhirVersion: 'R4')]
class LocBoundaryGeojsonExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var Attachment|null valueAttachment Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Attachment', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment $valueAttachment = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/location-boundary-geojson',
		    value: $this->valueAttachment,
		);
	}
}
