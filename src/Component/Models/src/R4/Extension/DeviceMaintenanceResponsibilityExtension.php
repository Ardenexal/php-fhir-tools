<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Orders and Observations
 * @see http://hl7.org/fhir/StructureDefinition/device-maintenanceresponsibility
 * @description Extension containing the information about the person and/or organization responsible for the maintenance of the device.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/device-maintenanceresponsibility', fhirVersion: 'R4')]
class DeviceMaintenanceResponsibilityExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var Reference|null person Responsible individual */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $person = null,
		/** @var Reference|null organization Responsible organization */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $organization = null,
		?string $id = null,
	) {
		$subExtensions = [];
		if ($this->person !== null) {
		    $subExtensions[] = new Extension(url: 'person', value: $this->person);
		}
		if ($this->organization !== null) {
		    $subExtensions[] = new Extension(url: 'organization', value: $this->organization);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/device-maintenanceresponsibility',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$person = null;
		$organization = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'person' && $ext->value instanceof Reference) {
		        $person = $ext->value;
		    }
		    if ($extUrl === 'organization' && $ext->value instanceof Reference) {
		        $organization = $ext->value;
		    }
		}

		return new static($person, $organization, $id);
	}
}
