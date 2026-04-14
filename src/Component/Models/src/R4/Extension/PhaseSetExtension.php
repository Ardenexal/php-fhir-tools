<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Orders and Observations
 * @see http://hl7.org/fhir/StructureDefinition/observation-geneticsPhaseSet
 * @description Phase set information.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsPhaseSet', fhirVersion: 'R4')]
class PhaseSetExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var UriPrimitive|null idSlice Phase set ID */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $idSlice = null,
		/** @var array<Reference> molecularSequence Phase set sequence */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isArray: true)]
		public array $molecularSequence = [],
		?string $id = null,
	) {
		$subExtensions = [];
		if ($this->idSlice !== null) {
		    $subExtensions[] = new Extension(url: 'Id', value: $this->idSlice);
		}
		foreach ($this->molecularSequence as $v) {
		    $subExtensions[] = new Extension(url: 'MolecularSequence', value: $v);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsPhaseSet',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$idSlice = null;
		$molecularSequence = [];

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'Id' && $ext->value instanceof UriPrimitive) {
		        $idSlice = $ext->value;
		    }
		    if ($extUrl === 'MolecularSequence' && $ext->value instanceof Reference) {
		        $molecularSequence[] = $ext->value;
		    }
		}

		return new static($idSlice, $molecularSequence, $id);
	}
}
