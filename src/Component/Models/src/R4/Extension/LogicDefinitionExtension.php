<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/cqf-logicDefinition
 * @description Represents a specific logic definition used by the artifact. When logic is referenced from knowledge artifacts, this extension allows each referenced definition to be represented independently so that consumers know which specific expressions are being referenced (i.e. not all expressions in a given library are always used), as well as allows processing applications (such as narrative generation) to easily surface the definitions to provide capabilities such as navigation.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-logicDefinition', fhirVersion: 'R4')]
class LogicDefinitionExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var StringPrimitive libraryName Which library */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $libraryName,
		/** @var StringPrimitive name Which definition */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $name,
		/** @var StringPrimitive statement Complete declaration statement */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $statement,
		/** @var StringPrimitive|null displayCategory What category? */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $displayCategory = null,
		/** @var int|null displaySequence What order? */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
		public ?int $displaySequence = null,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'libraryName', value: $this->libraryName);
		$subExtensions[] = new Extension(url: 'name', value: $this->name);
		$subExtensions[] = new Extension(url: 'statement', value: $this->statement);
		if ($this->displayCategory !== null) {
		    $subExtensions[] = new Extension(url: 'displayCategory', value: $this->displayCategory);
		}
		if ($this->displaySequence !== null) {
		    $subExtensions[] = new Extension(url: 'displaySequence', value: $this->displaySequence);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/cqf-logicDefinition',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$libraryName = null;
		$name = null;
		$statement = null;
		$displayCategory = null;
		$displaySequence = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'libraryName' && $ext->value instanceof StringPrimitive) {
		        $libraryName = $ext->value;
		    }
		    if ($extUrl === 'name' && $ext->value instanceof StringPrimitive) {
		        $name = $ext->value;
		    }
		    if ($extUrl === 'statement' && $ext->value instanceof StringPrimitive) {
		        $statement = $ext->value;
		    }
		    if ($extUrl === 'displayCategory' && $ext->value instanceof StringPrimitive) {
		        $displayCategory = $ext->value;
		    }
		    if ($extUrl === 'displaySequence' && is_int($ext->value)) {
		        $displaySequence = $ext->value;
		    }
		}

		return new static($libraryName, $name, $statement, $displayCategory, $displaySequence, $id);
	}
}
