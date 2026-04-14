<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/timing-daysOfCycle
 * @description Days of a possibly repeating cycle on which the action is to be performed. The cycle is defined by the first action with a timing element that is a parent of the daysOfCycle.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/timing-daysOfCycle', fhirVersion: 'R4')]
class DaysOfCycleExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var array<int> day What day to perform */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: true)]
		public array $day = [],
		?string $id = null,
	) {
		$subExtensions = [];
		foreach ($this->day as $v) {
		    $subExtensions[] = new Extension(url: 'day', value: $v);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/timing-daysOfCycle',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$day = [];

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'day' && is_int($ext->value)) {
		        $day[] = $ext->value;
		    }
		}

		return new static($day, $id);
	}
}
