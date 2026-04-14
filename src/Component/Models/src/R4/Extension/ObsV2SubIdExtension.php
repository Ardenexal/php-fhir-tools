<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Orders and Observations
 * @see http://hl7.org/fhir/StructureDefinition/observation-v2-subid
 * @description A complex extension matching the structure of the V2 OG data type.  This is used in the v2-to-fhir mapping IG to capture and preserve the OBX-4 Sub-Id details.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-v2-subid', fhirVersion: 'R4')]
class ObsV2SubIdExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var StringPrimitive|null originalSubIdentifier Original Sub-Identifier */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $originalSubIdentifier = null,
		/** @var string|null group Group */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
		public ?string $group = null,
		/** @var string|null sequence Sequence */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
		public ?string $sequence = null,
		/** @var StringPrimitive|null identifier Identifier */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $identifier = null,
		?string $id = null,
	) {
		$subExtensions = [];
		if ($this->originalSubIdentifier !== null) {
		    $subExtensions[] = new Extension(url: 'original-sub-identifier', value: $this->originalSubIdentifier);
		}
		if ($this->group !== null) {
		    $subExtensions[] = new Extension(url: 'group', value: $this->group);
		}
		if ($this->sequence !== null) {
		    $subExtensions[] = new Extension(url: 'sequence', value: $this->sequence);
		}
		if ($this->identifier !== null) {
		    $subExtensions[] = new Extension(url: 'identifier', value: $this->identifier);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/observation-v2-subid',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$originalSubIdentifier = null;
		$group = null;
		$sequence = null;
		$identifier = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'original-sub-identifier' && $ext->value instanceof StringPrimitive) {
		        $originalSubIdentifier = $ext->value;
		    }
		    if ($extUrl === 'group' && is_string($ext->value)) {
		        $group = $ext->value;
		    }
		    if ($extUrl === 'sequence' && is_string($ext->value)) {
		        $sequence = $ext->value;
		    }
		    if ($extUrl === 'identifier' && $ext->value instanceof StringPrimitive) {
		        $identifier = $ext->value;
		    }
		}

		return new static($originalSubIdentifier, $group, $sequence, $identifier, $id);
	}
}
