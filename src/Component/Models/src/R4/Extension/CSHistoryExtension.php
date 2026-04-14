<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Terminology Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/codesystem-history
 * @description Information on changes made to the Value Set Definition over time, and also has a contained audit trail of all such changes.  This extension is retired.  Recording history of a value set definition should be done using provenance bundles instead.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/codesystem-history', fhirVersion: 'R4')]
class CSHistoryExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var StringPrimitive|null name The name of this set of history entries */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $name = null,
		/** @var array<Base64BinaryPrimitive> revision Audit of all changes for a history entry */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive', isArray: true)]
		public array $revision = [],
		?string $id = null,
	) {
		$subExtensions = [];
		if ($this->name !== null) {
		    $subExtensions[] = new Extension(url: 'name', value: $this->name);
		}
		foreach ($this->revision as $v) {
		    $subExtensions[] = new Extension(url: 'revision', value: $v);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/codesystem-history',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$name = null;
		$revision = [];

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'name' && $ext->value instanceof StringPrimitive) {
		        $name = $ext->value;
		    }
		    if ($extUrl === 'revision' && $ext->value instanceof Base64BinaryPrimitive) {
		        $revision[] = $ext->value;
		    }
		}

		return new static($name, $revision, $id);
	}
}
